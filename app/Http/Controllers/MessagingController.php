<?php

namespace App\Http\Controllers;

use App\Http\Drivers\Freelancer;
use App\Notification;
use App\User;
use App\UserLead;
use App\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagingController extends Controller
{
    private $user, $userMessage, $userLead, $freelancer, $notifications;

    public function __construct()
    {
        $this->userMessage = new UserMessage();
        $this->notifications = new Notification();
        $this->user = new User();
        $this->userLead = new UserLead();
        $this->freelancer = new Freelancer();
    }

    public function index()
    {
        session()->forget(['thread', 'thread_id']);
        $threadExists = false;
        if (request('id') && Auth::user()->role_id == USER) {
            $threadExists = $this->userMessage->where('thread_id', request('id'))->where('user_id', Auth::id())->exists();
        }
        $heading = 'Messsages';
        $usersList = $this->user->newQuery()->where('role_id', USER)->get();
        $response = $this->getThreads();
        if ($response->status == 'error') {
            return view('messaging.index', compact('heading', 'response', 'usersList'))->with('error', $response->message);
        }
        $threads = view('messaging.threads', compact('response'))->render();
        if (!$threadExists && Auth::user()->role_id == USER && request('id')) {
            return view('messaging.index', compact('threads', 'heading', 'usersList'))->with('error', 'Access Denied to this thread');
        }
        return view('messaging.index', compact('threads', 'heading', 'usersList'));
    }

    private function getThreads()
    {
        $data = [
            // 'limit' => 5000,
            'unread_count' => true,
            'last_message' => true,
            'unread_count' => true,
            'message_count' => true,
            'user_details' => true,
            'unread_thread_count' => true,
            'context_details' => true,
            'folders[]' => 0
        ];
        if (Auth::user()->role_id == USER) {
            $users = NULL;
            $data['thread_types[]'] = 'private_chat';
            $userLeads = $this->userLead->newQuery()->where('user_id', Auth::id())->get();
            $threads = [];
            $data['context_type'] = 'project';
            if (count($userLeads) > 0) {
                $params = "";
                foreach ($userLeads as $key => $lead) {
                    $params .= "contexts[]=" . $lead->project_id . "&";
                }
                $response = $this->freelancer->get('messages/0.1/threads?' . $params, $data);
                if (!empty($response) && $response->status == 'success') {
                    $threads = $response->result->threads;
                    $users = $response->result->users;
                } else return $response;
            }
            $userMessages = $this->userMessage->newQuery()->where('user_id', Auth::id())->get();
            if (count($userMessages) > 0) {
                unset($data['context_type']);
                $params = "";
                foreach ($userMessages as $key => $message) {
                    $params .= "threads[]=" . $message->thread_id . '&';
                }
                $response = $this->freelancer->get('messages/0.1/threads?' . $params, $data);
                if (!empty($response) && $response->status == 'success') {
                    $threads = array_merge($threads, $response->result->threads);
                    $users = (object) array_merge(
                        (array) $users,
                        (array) $response->result->users
                    );
                } else return $response;
            }
            $threads = array_unique((array) $threads, SORT_REGULAR);
            $users = array_unique((array) $users, SORT_REGULAR);
            $response->result->threads = $threads;
            $response->result->users = $users;
        } else {
            $response = $this->freelancer->get('messages/0.1/threads', $data);
        }
        if ($response->status == 'success') {
            return sortUnreadThreads($response);
        } else return $response;
    }

    public function getUnreadMessages(Request $request)
    {
        $response = $this->getThreads();
        if ($response && $response->status == 'error') {
            return ['success' => false];
        }
        if ($request->messageView == 'false') {
            return ['success' => true, 'count' => $response->result->unread_thread_count];
        } else {
            return ['success' => true, 'threads' => view('messaging.threads', compact('response'))->render()];
        }
    }

    public function searchThreads(Request $request)
    {
        $data = [
            'thread_details' => true,
            'user_details' => true,
            'context_details' => true
        ];
        if (!empty($request->search))
            $data['query'] = $request->search;
        $response = $this->freelancer->get('messages/0.1/threads/search', $data);
        if ($response->status == 'error') {
            return [
                'success' => false,
                'message' => $response->message
            ];
        }
        return [
            'success' => true,
            'message' => view('messaging.threads', compact('response'))->render()
        ];
    }

    public function messages(Request $request)
    {

        $data  = [
            'threads[]' => $request->thread_id,
            'action' => 'read'
        ];
        $this->freelancer->put('messages/0.1/threads', $data);
        $data  = [
            'threads[]' => $request->thread_id,
            'contexts[]' => $request->project_id,
            'context_type' => 'project'
        ];
        $messages = $this->freelancer->get('messages/0.1/messages', $data);
        $data  = [
            'thread_id' => $request->thread_id,
            'thread_attachments' => true
        ];
        $attachments = $this->freelancer->get('messages/0.1/threads/' . $request->thread_id, $data);
        $assignedUser = $this->userMessage->newQuery()->where('thread_id', $request->thread_id)->with('user')->first();
        $user = $request->user;

        if (empty(session('thread_id')) || session('thread_id') != $request->thread_id) {
            session(['thread' => $messages->result->messages, 'thread_id' => $request->thread_id]);
        } else {
            $messages->result->messages = $this->check_diff_multi((array) session('thread'), (array) $messages->result->messages);
        }
        $userLead = $this->userLead->newQuery()->where('lead_id', $request->project_id)->where('status', '!=', PENDING)->first();
        $view = view('messaging.messages', compact('messages', 'user'));
        return ['html' => $view->render(), 'assignedUser' => $assignedUser, 'project' => $userLead];
    }

    private function check_diff_multi($array1, $array2)
    {
        $result = array();
        foreach ($array1 as $key => $val) {
            if (isset($array2[$key])) {
                if (is_array($val) && $array2[$key]) {
                    $result[$key] = check_diff_multi($val, $array2[$key]);
                }
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    public function sendMessage(Request $request)
    {
        $data = [
            'message' => $request->message,
        ];
        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $file->getRealPath(),
                    'mimeType' => $file->getMimeType()
                ];
            }
        }
        $message = $this->freelancer->post('messages/0.1/threads/' . $request->thread_id . '/messages', $data, [], $files);
        return $message->status;
    }

    public function downloadAttchment(Request $request)
    {
        $file = $this->freelancer->downloadAttachment("messages/0.1/messages/" . $request->message_id . "/" . rawUrlEncode($request->filename), $request->filename);
        if ($file) {
            $return = [
                'success' => true,
                'file' => url('attachments/messages/' . $request->filename)
            ];
        } else {
            $return = [
                'success' => false
            ];
        }
        return $return;
    }

    public function sendAttachment(Request $request)
    {
        $data = [
            'files[]' => "",
        ];
        dd($data);
        $message = $this->freelancer->post('messages/0.1/threads/' . $request->thread_id . '/messages', $data);
    }

    public function assignThread(Request $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $this->userMessage->newQuery()->where('user_id', $inputs['user_id'])->where('thread_id', $inputs['thread_id'])->delete();
            $userMessage = $this->userMessage->newInstance();
            $userMessage->thread_id = $inputs['thread_id'];
            $userMessage->user_id = $inputs['user_id'];
            if ($userMessage->save()) {
                $notification = $this->notifications->newInstance();
                $notification->message = Auth::user()->first_name . ' ' . Auth::user()->last_name . ' assigned a thread to you with ' . $inputs['user']['display_name'];
                $notification->created_by = Auth::id();
                $notification->user_id = $inputs['user_id'];
                $notification->thread_id = $inputs['thread_id'];
                if ($notification->save()) {
                    DB::commit();
                }
            } else {
                DB::rollback();
                return response()->json(['suceess' => false], 400);
            }
            return response()->json(['suceess' => true], 200);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->geMessage()], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['suceess' => false, 'message' => $e->geMessage()], 500);
        }
    }
}
