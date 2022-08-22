<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Todo</title>
    <link rel="stylesheet" href="{{asset('css/user.css')}}" />

    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    @include('layout.nav')
    <!-- Modal Invite Users-->
    <div class="modal fade" id="inviteusers" tabindex="-1" role="dialog" aria-labelledby="inviteusers" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Invite new members</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('invite.member')}}" method="POST" id="form_invite_id">
                        @csrf
                        <div class="form-group">
                            <div class="d-flex ">
                                <i class='bx bx-envelope mt-1 mr-1'></i>
                                <label for="email_input_id">
                                    Invite with email
                                </label>
                            </div>
                            <input type="email" class="form-control" id="email_input_id" name="email" placeholder="Enter email address">
                            <span id="email_span_error_id" style="display: none; color: red;"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitInviteMember();" id="inviteuser">Invite</button>
                </div>
            </div>
        </div>
    </div>
    <!-- thong bao -->
    @if (Session::has('error') || Session::has('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="position: absolute; bottom: 0; right: 10px;  min-height: 200px; width:300px">
        <div id="basicToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" >
            @if (Session::has('error') )
            <div class="toast-header bg-danger text-light">
            @else
            <div class="toast-header bg-success text-light">
            @endif
                <h6 class="my-0">Success</h6>
            </div>
            <div class="toast-body" >
               {{Session::get('success') ? Session::get('success'): Session::get('error')}} 
            </div>
        </div>
    </div>
    @endif

    <div class="profile-user" onclick="menuToggle();">
        <i class="bx bxs-user"></i>
    </div>
    <div class="conent">
    </div>
    <script>
        (function() {
            const notification = document.querySelector('#basicToast');
            if (notification) {
                new bootstrap.Toast(document.querySelector('#basicToast')).show();
            }
        }());

        function menuToggle() {
            const toggleMenu = document.querySelector('.menu-user');
            toggleMenu.classList.toggle('active')
        };

        function opentab(tabName, element) {
            var i;
            var x = document.getElementsByClassName("tab-body");
            var y = document.getElementsByClassName("tab-indicator");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            for (i = 0; i < y.length; i++) {
                y[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            element.classList.add("active");
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>
        function submitInviteMember() {
            const value = jQuery('input#email_input_id').val();
            var re = /\S+@\S+\.\S+/;
            console.log(re.test(value));
            if (value && re.test(value)) {
                jQuery('form#form_invite_id').submit();
            } else if (!value) {
                jQuery('span#email_span_error_id').text('Email is not required');
            } else if (!re.test(value)) {
                jQuery('span#email_span_error_id').text('Invalid email format');
            }
            jQuery('span#email_span_error_id').css('display', 'block');
        }
    </script>
</body>

</html>