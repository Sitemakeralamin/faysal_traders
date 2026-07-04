@extends('user.inc.master')

@section('title')
	Wholesale
@endsection

@section('content')
<style>
    @media only screen and (max-width: 768px) {
        .sign_in_top{
            padding-top: 0px !important;
        }
    }
</style>
    <!-- Start login section  -->
    <div class="login__section py-5 border-top sign_in_top">
        <div class="container">
            <form method="POST" action="{{ route('wholesale.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="login__section--inner">
                    <input type="hidden" name="register_type" id="register_type" value="phone">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="col">
                                <div class="account__login register">
                                    <div class="account__login--header mb-25 text-center">
                                        <h2 class="account__login--header__title h3 mb-10">Send Quatation For Wholesale</h2>
                                    </div>
                                    <div class="account__login--inner">
                                        <input class="account__login--input" placeholder="Name" name="name" value="{{old('name')}}" required type="text">


                                    <input class="account__login--input mb-0" id="phone" name="phone" required value="{{old('phone')}}"  placeholder="Please Enter your phone number" minlength="11" maxlength="11" type="number">
                                    <p class="mt-3"> Provide Visiting Card or Teade Licence * </p>
                                    <input class="account__login--input" placeholder="Visiding or Teade Licence" name="image" required type="file">
                                        
                                        <button class="account__login--btn primary__btn mb-10" type="submit">Send Quatation</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3"></div>
                       

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End login section  -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>

    @if(session('register_type'))
        $( document ).ready(function() {
            use_register_type('{{session('register_type')}}');
        });
    @endif

    function use_register_type(type) {

        if(type == 'email') {
            $('#use_mobile_btn').show();
            $('#use_email_btn').hide();
            $('#phone').hide();
            $('#email').show();
            $("#email").prop('required',true);
            $("#phone").prop('required',false);
            $('#register_type').val('email');
        }
        else if(type == 'phone') {
            $('#use_mobile_btn').hide();
            $('#use_email_btn').show();
            $('#phone').show();
            $('#email').hide();
            $("#email").prop('required',false);
            $("#phone").prop('required',true);
            $('#register_type').val('phone');
        }
    }
</script>


@endsection



