<?php

use App\Permission;
use App\RolePermission;

function timeAgo($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function filterUser($users, $members)
{
    $authId = session('freelancerUser')->id;
    $ownerId = 0;
    foreach ($members as $key => $member) {
        if ($authId != $member) {
            $ownerId = $member;
        }
    }
    foreach ($users as $key => $user) {
        if ($user->id == $ownerId) {
            return $user;
        }
    }
}

function getPermissionId()
{
    $id  = Permission::where('module', Request::segment(1))->first();
    return  Request::segment(1) == 'profile' ? 0 : $id->id;
}

function checkPermission($permission, $action)
{
    if ($permission == 0)
        return true;
    return RolePermission::where('permission_id', $permission)->where('role_id', Auth::user()->role_id)->where('action_id', $action)->exists();
}

function sortUnreadThreads($response)
{
    usort($response->result->threads, function ($a, $b) {
        // dd($a, $b);
        return strcmp($b->time_updated, $a->time_updated);
    });
    return $response;
}

function getBidStatus($bid)
{
    $return = NULL;
    switch ($bid->award_status) {
        case 'awarded':
            $return = ['status' => AWARDED, 'slug' => 'awarded'];
            break;
        case 'rejected':
            $return = ['status' => REJECTED, 'slug' => 'rejected'];
            break;
        case 'revoked':
            $return = ['status' => REVOKED, 'slug' => 'revoked'];
            break;
        case 'canceled':
            $return = ['status' => CANCELED, 'slug' => 'canceled'];
            break;
        default:
            $return = ['status' => PENDING, 'slug' => 'pending'];
            break;
    }
    return $return;
}
