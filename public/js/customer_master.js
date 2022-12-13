$( document ).ready(function() {
    let x = $(location).attr('pathname').split('/');
    let add_action = x.includes("add");
    let edit_action = x.includes("edit");

    $("#trade_name, #mall, #branch, #first_name, #last_name").keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
    
    if(add_action){

        //fieldAutoCompleteOff();

        //get channel
        $("#channels_id").change(function() {
            let channel = $("#channels_id option:selected").text();
            let channel_value = channel.search("EMPLOYEE");

            if(channel_value == 0){ //true
                fieldResetReadOnly();
            }
            else{
                fieldRestrict();
            }
        });

        
    }
    else if(edit_action){

        //fieldAutoCompleteOff();

        $("#channels_id").change(function() {
            let channel = $("#channels_id option:selected").text();
            let channel_value = channel.search("EMPLOYEE");

            if(channel_value == 0){
                fieldResetReadOnly();
            }
            else{
                fieldRestrict();
            }
        });
    }
});

function fieldResetReadOnly() {
    $("#trade_name").val("");
    $("#mall").val("");
    $("#branch").val("");

    $("#trade_name").prop('readonly', true);
    $("#mall").prop('readonly', true);
    $("#branch").prop('readonly', true);
}

function fieldRestrict() {
    $("#trade_name").prop('readonly', false);
    $("#mall").prop('readonly', false);
    $("#branch").prop('readonly', false);
}

function fieldAutoCompleteOff() {
    $("#trade_name").attr('autocomplete','off');
    $("#mall").attr('autocomplete','off');
    $("#branch").attr('autocomplete','off');
}