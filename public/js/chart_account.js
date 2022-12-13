$(document).ready(function() {
    
    let x = $(location).attr('pathname').split('/');
    let add_action = x.includes("add");
    let edit_action = x.includes("edit");
    
    $('#account_name').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });

    var accountType = document.getElementById('account_type');
    var header = ["ASSETS","LIABILITIES","EQUITY","INCOME","COST OF SALES","EXPENSE","OTHER INCOME","OTHER EXPENSE"];
    var detailed = ["ACCOUNTS PAYABLE","ACCOUNTS RECEIVABLE","BANK","CREDIT CARD","FIXED ASSET","LONG TERM LIABILITY","OTHER ASSET","OTHER CURRENT ASSET","OTHER CURRENT LIABILITY","OTHER LIABILITY"];

    loadAccountType();
    loadSelectedAccountType();

    $('#header').change(function () {
        //reset first all options
        $('#account_type').empty().append('<option value="">**Please select an Account Type</option>');
        loadAccountType();
    });

    $('#account_number').keyup(function() {
        var headerValue = document.getElementById('header').value;
        var accountNumber = this.value;
        var firstAccountNumber = accountNumber.substring(0, 1);

        if(headerValue == 'H'){
            $('#account_type').empty().append('<option value="">**Please select an Account Type</option>');
            var option = document.createElement("option");
            switch (firstAccountNumber) {
                case '1':
                    option.value = header[0];
                    option.text = header[0];
                    accountType.add(option);
                    break;
                case '2':
                    option.value = header[1];
                    option.text = header[1];
                    accountType.add(option);
                    break;
                case '3':
                    option.value = header[2];
                    option.text = header[2];
                    accountType.add(option);
                    break;
                case '4':
                    option.value = header[3];
                    option.text = header[3];
                    accountType.add(option);
                    break;
                case '5':
                    option.value = header[4];
                    option.text = header[4];
                    accountType.add(option);
                    break;
                case '6':
                    option.value = header[5];
                    option.text = header[5];
                    accountType.add(option);
                    break;
                case '7':
                    option.value = header[6];
                    option.text = header[6];
                    accountType.add(option);
                    break;
                case '8': case '9':
                    option.value = header[7];
                    option.text = header[7];
                    accountType.add(option);
                    break;                
                default:
                    break;
            }
        }
        
    });

    function loadAccountType() {
        if(add_action){
            var headerValue = document.getElementById('header').value;
            if(headerValue == 'H'){
                //create and append the options
                for (var i = 0; i < header.length; i++) {
                    var option_h= document.createElement("option");
                    option_h.value = header[i];
                    option_h.text = header[i];
                    accountType.add(option_h);
                }
            }
            else{
                //create and append the options
                for (var a = 0; a < detailed.length; a++) {
                    var option_d = document.createElement("option");
                    option_d.value = detailed[a];
                    option_d.text = detailed[a];
                    accountType.add(option_d);
                }
            }
        }
    }

    function loadSelectedAccountType() {
        if(edit_action){
            var headerValue = document.getElementById('header').value;
            var accountNumber = document.getElementById('account_number').value;
            var firstAccountNumber = accountNumber.substring(0, 1);
    
            if(headerValue == 'H' && accountNumber !== ''){
                $('#account_type').empty();
                var option = document.createElement("option");
                switch (firstAccountNumber) {
                    case '1':
                        option.value = header[0];
                        option.text = header[0];
                        accountType.add(option);
                        break;
                    case '2':
                        option.value = header[1];
                        option.text = header[1];
                        accountType.add(option);
                        break;
                    case '3':
                        option.value = header[2];
                        option.text = header[2];
                        accountType.add(option);
                        break;
                    case '4':
                        option.value = header[3];
                        option.text = header[3];
                        accountType.add(option);
                        break;
                    case '5':
                        option.value = header[4];
                        option.text = header[4];
                        accountType.add(option);
                        break;
                    case '6':
                        option.value = header[5];
                        option.text = header[5];
                        accountType.add(option);
                        break;
                    case '7':
                        option.value = header[6];
                        option.text = header[6];
                        accountType.add(option);
                        break;
                    case '8': case '9':
                        option.value = header[7];
                        option.text = header[7];
                        accountType.add(option);
                        break;                
                    default:
                        break;
                }
            }else{
                //create and append the selected options
                
            }
        }
    }
    
});