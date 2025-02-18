@extends('layout')
@section('content')
    <div class="container-xxl py-5" id="candidat">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3"> Candidats</h6>
                <h1 class="mb-5">Les Candidats</h1>
            </div>
            <div class="row g-4">
                @forelse ($candidats as $item)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item bg-light">
                            <div class="overflow-hidden">
                                <img class="img-fluid" src="{{ Storage::url($item->photo) }}" alt="">
                            </div>
                            <div class="text-center p-4">
                                <h5 class="mb-0">{{ $item->name }}</h5>
                                <small style="font-size: 20px">Candidat N°{{ $item->id }}:
                                    {{ $item->votes_sum_numbre_of_vote ? $item->votes_sum_numbre_of_vote : 0 }}
                                    @if ($item->votes_sum_numbre_of_vote > 1)
                                        Votes
                                    @else
                                        Vote
                                    @endif
                                </small><br>
                                <a href="{{ route('candidat', $item->id) }}" class="btn btn-lg btn-primary">Votez</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-danger">
                        Aucun candidat enregistré
                    </div>
                @endforelse
            </div>

        </div>
    </div>
    <!-- Team End -->
@endsection
