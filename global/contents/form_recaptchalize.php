<script src='https://www.google.com/recaptcha/api.js'></script>
<button
    id="btn-recaptcha-submit"
    class="g-recaptcha"
    data-sitekey="<?=RECAPTCHA_PUB_KEY?>"
    data-badge="bottomleft"
    data-callback="recaptchaSubmit"
    style="display:none"
    >
</button>
<script>
    $(function(){
        var btn = $('#btn-recaptcha-submit');
        var form = btn.parents('form');
        form.submit(function(e){
            var isCaptchaChecked = (grecaptcha && grecaptcha.getResponse().length !== 0);
            if(!isCaptchaChecked){
                btn.click();
                e.preventDefault();
                return false;
            }
        });
    });
    function recaptchaSubmit(){
        var btn = $('#btn-recaptcha-submit');
        var form = btn.parents('form');
            $('<input type="submit">').hide().appendTo(form).click().remove();
            grecaptcha.reset();
    }
</script>