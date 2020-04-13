@extends('layouts.app')

@section('content')
   <div class="container">
      <x-navigation></x-navigation>
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card">
               <div class="card-header">Space: {{$space->title}} </div>

               <div class="card-body">
                  <div id="mapContainer" style="height: 500px"></div>
               </div>
            </div>

            <div class="card mb-3">
               <div class="card-body">
                  <h3> {{$space->title}} </h3>
                  <span> {{$space->address}} </span>
                  <p> {{$space->description}} </p>
                  <div id="summary"> </div>
               </div>
            </d>
         </div>
      </div>
   </div>
@endsection

@push('script')
   <script>
      window.action = "direction"
   </script>
@endpush