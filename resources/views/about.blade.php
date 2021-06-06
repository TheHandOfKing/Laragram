@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row mt-5 mb-5">
      <div class="col-12 mt-5">
          <div class="card">
              <div class="card-header bg-info">
                  <h3 class="text-white">About Me</h3>
              </div>
              <div class="card-body">
                <div class="container">
                    <div class="row mb-5">
                      <div class="col-sm-12 col-md-6 d-flex flex-column">
                        <img src="{{url('/storage/profile14.png')}}" style="width: 100px" alt="authorSite">

                        <a href="{{url('/storage/documentation.docx')}}">documentation</a>
                      </div>
            
                    </div>
                    <div class="row">
                      <p class="text-black text-center">My name is Aleksandar Marjanovic and i made this site as a project for the ICT uni, IT course, Web Programming PHP 2 subject. This is my official third or fourth site, made by using laravel and vue, which you can all see in the documentation. Appart from programming my passion is gaming and basketball, and i tend to keep up with both. Aside from programming i am into basketball and the only thing i can't allow myself to be out of shape. I was born in Belgrade, Serbia, and i currently live in Obrenovac.</p>
                    </div>
                    <hr>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection