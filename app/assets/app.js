var validate = {
    notEmpty: function (el) {
        return $.trim(el.val()) !== '';
    },
    isEmail: function (el) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(el.val()).toLowerCase());
    },
    RegExp: function (el, regexp) {
        return !regexp.test(String(el.val()).toLocaleLowerCase());
    },
    isChecked: function (el) {
        return el.is(':checked');
    }
}

var validator = {
    terms: $('[name=terms]'),
    subscribe: $('[name=email]'),
    template: $('<div class="error"></div>'),
    rules: {
        'terms':{
            'isChecked':'You must accept the terms and conditions'
        },
        'email':{
            'notEmpty':'Email address is required',
            'isEmail':'Please provide a valid e-mail address',
            'RegExp':[/.*\.cc$/, 'We are not accepting subscriptions from Colombia emails']
        },
    },
    init: function () {
        let validator = this;
        this.subscribe.keyup(function () {
            validator.validateForm();
        });

        this.terms.change(function () {
            validator.validateForm();
        });
    },
    validate: function (el) {
        if(this.rules.hasOwnProperty(el.attr('name'))) {
            let rules = this.rules[el.attr('name')];
            this.clearErrors(el);
            for (rule in rules) {
                if (validate.hasOwnProperty(rule)) {
                    console.log(rule);
                    if($.isArray(rules[rule])) {
                        if(!eval(validate[rule](el, rules[rule][0]))) {
                            this.addError(el, rules[rule][1]);
                        }
                        continue;
                    }
                    if(!eval(validate[rule](el))) {
                        this.addError(el, rules[rule]);
                    }
                }
            }
        }
    },
    clearErrors: function (el) {
        let formfield = el.closest('.form-control');
        formfield.removeClass('has-error');
        formfield.find('.error').remove();
    },
    addError: function (el, message) {
        let error = this.template.clone();
        let formfield = el.closest('.form-control');
        formfield.addClass('has-error');
        formfield.append(error.text(message));
    },
    validateForm: function () {
        validator.validate(this.terms);
        validator.validate(this.subscribe);
        $('input[type=submit]').prop('disabled', $('.form-control.has-error').length>0);
    }
}

$(document).ready(function (){
   validator.init();
});