import $ from 'jquery';
export default function() {
    return {
        registerEvents: function()
        {
            let registerForm = $('#register-form');
            registerForm.on('submit', function(event){
                let password = registerForm.find('#password').val();
                let passwordConfirm = registerForm.find('#confirm_password').val();
                if(password !== passwordConfirm){
                    event.preventDefault();
                    return false;
                }
            });

            //
            $('#confirm_password')
                .on('focusin', function(){
                    $('#confirm_password_error').addClass('d-none');
                })
                .on('focusout', function(){
                    let password = registerForm.find('#password').val();
                    let passwordConfirm = registerForm.find('#confirm_password').val();
                    if(password === passwordConfirm){
                        return;
                    }

                    $('#confirm_password_error').removeClass('d-none');
                })
            ;
        }
    }
};