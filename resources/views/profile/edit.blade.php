@extends('layouts.app')
@section('content')
<div class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
