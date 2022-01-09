@extends('layouts.app')

@section('content')
   <div class="row mt-5">
       <div class="col-md-8 mx-auto">
           <!-- navtabs -->
           <div class="row">
               <div class="col bg-white">
                  <div class="container">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active nav-item">
                            <a href="#" class="nav-link">
                                <span class="badge badge-primary">1</span>
                                Student's Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">About</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Contact</a>
                        </li>
                    </ul>
                 </div>
               </div>
           </div>
           <!-- end of navtabs -->
           <div class="row bg-info p-3 text-white">
               <div class="container">
                   <form action="{{ route('register') }}" method="POST">
                    @csrf
                       <div class="form-group row">
                          <div class="col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" name="firstname" value="{{ old('firstname') }}" placeholder="First Name" class="form-control @error('firstname') is-invalid @enderror">
                            @error('firstname')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="col-md-6">
                            <label for="fullname">Last Name</label>
                            <input type="text" name="lastname" value="{{ old('lastname') }}" placeholder="Last Name" class="form-control @error('lastname') is-invalid @enderror">
                            @error('lastname')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                       </div>
                       <div class="form-group row">
                          <div class="col-md-4">
                            <label for="dateOfBirth">Date Of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control @error('date_of_birth') is-invalid @enderror">
                            @error('date_of_birth')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="col-md-4">
                            <label for="placeOfBirth">Place Of Birth</label>
                            <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="Place Of Birth" class="form-control @error('place_of_birth') is-invalid @enderror">
                            @error('place_of_birth')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="col-md-4">
                            <label for="country">Country</label>
                            <select name="country" id="country" value="{{ old('country') }}" class="form-control @error('country') is-invalid @enderror">
                                <option value="">Select Country</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Nigeria">Nigeria</option>
                            </select>
                            @error('country')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                       </div>
                       <div class="form-group row">
                          <div class="col-md-4">
                            <label for="sex">Gender</label>
                            <select name="gender" id="gender" value="{{ old('gender') }}" class="form-control @error('gender') is-invalid @enderror">
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="col-md-4">
                            <label for="phoneNumber">Phone Number</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number" class="form-control @error('phone_number') is-invalid @enderror">
                            @error('phone_number')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="col-md-4">
                            <label for="placeOfBirth">Region</label>
                            <select name="region" id="region" value="{{ old('region') }}" class="form-control @error('region') is-invalid @enderror">
                                <option value="">Select Region</option>
                                <option value="South West">South West</option>
                                <option value="Littoral">Littoral</option>
                            </select>
                            @error('region')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                       </div>
                       <div class="form-group">
                           <input type="submit" value="Next" class="btn btn-primary">
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
@endsection
