<div class="projects">
    {{-- {{ dd($projects[0]) }} --}}
    @foreach ($projects as $project)
    <a href="{{ url('leads/'.$project->id.'/details') }}" class="text-dark">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10 position-relative pl-5">
                        <div class="project-type-icon">
                            @if($project->type == 'fixed')
                            <i class="fas fa-desktop text-primary" style="font-size: 20px"></i>
                            @else
                            <i class="fas fa-clock text-primary" style="font-size: 20px"></i>
                            @endif
                        </div>
                        <h4>{{ $project->title }}</h4>
                        <p class="small">{{ $project->preview_description ? $project->preview_description : $project->description  }}</p>
                        <p class="small"><i class="fas fa-hourglass-start text-gray-dark mr-2"></i> {{  ucfirst(implode(' ',explode('_', $project->frontend_project_status))) }} {{ timeAgo(date('Y-m-d H:i:s',$project->time_submitted)) }} @if($project->bid_stats)— {{ $project->bid_stats->bid_count }} Bids @endif</p>
                        @if(!empty($project->jobs))
                        <p class="small">
                            @foreach ($project->jobs as $key => $job)
                            @if($job->name)
                            @if($loop->first)<i class="fas fa-tag text-gray-dark mr-2"></i>@endif
                            {{ $job->name }} @if(!$loop->last),@endif
                            @endif
                            @endforeach
                        </p>
                        @endif

                    </div>
                    <div class="col-md-2 pr-3 d-flex text-right align-items-top flex-column">
                        @if($project->currency->sign)<h5>{{ $project->currency->sign }}@if($project->budget->minimum){{ $project->budget->minimum }} @endif @if(!empty($project->budget->maximum))- {{ $project->currency->sign.$project->budget->maximum }} @endif</h5>@endif
                        @if($project->currency->code)<p>{{ $project->currency->code }}</p>@endif
                    </div>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
{{ $projects->links() }}
