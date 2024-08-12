@extends('layouts.app')
@section('content')
<section class="section ">
    <div class="row">
        <div class="col-lg-12">
            <x-card>
                {{ __("You're logged in!") }}
            </x-card>
        </div>
    </div>
</section>
@endsection
