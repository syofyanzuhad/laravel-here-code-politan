@extends('layouts.app')

@section('content')
   <div class="container">
      <x-space></x-space>
      <div class="row justify-content-center">
         <div class="col-md-8">

            @if (session('status'))
               <div class="alert alert-success" role="alert">
                  {{ session('status') }}
               </div>
            @endif

            <div class="card mb-2">
               <div class="card-header">Space</div>
            @foreach ($spaces as $space)
                  <div class="card-body">
                     <h5 class="card-title"> 
                        {{$space->title}}
                        @if ($space->user_id == Auth::user()->id)
                           <form action=" {{route('space.destroy', $space->id)}} " method="POST">
                              @csrf @method('DELETE')
                              <button type="submit" class="btn btn-danger float-right text-white" onclick="return confirm('Apakah anda yakin ?')">Delete</button>
                              <a href=" {{route('space.edit', $space->id)}} " class="btn btn-info float-right text-white">Edit</a>
                           </form>
                        @endif
                     </h5>
                     <h5 class="card-subtitle"> {{$space->address}} </h5>
                     <p class="card-text"> {{$space->desctription}} </p>
                     <a href="#" class="card-link" onclick="openDirection( {{$space->latitude}}, {{$space->longitude}} )">Direction</a>
                  </div>
                  <hr>
                  @endforeach
                  <div class="row justify-content-center">
                     {{ $spaces->links() }}
                  </div>
               </div>
         </div>
      </div>
   </div>
@endsection
