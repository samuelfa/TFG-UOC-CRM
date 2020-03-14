import $ from 'jquery';
export default function() {
    return {
        registerEvents: function(){
            $('#register-form').on('submit', function(event){
                let password = $(this).find('#password').val();
                let passwordConfirm = $(this).find('#confirm_password').val();
                if(password !== passwordConfirm){
                    //todo: display error message
                    event.preventDefault();
                    return false;
                }
            });
        }
    }
};