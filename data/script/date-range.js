/*function dateRange() {
    var start_date = document.getElementById("start-end");
    var end_date = document.getElementById("end-start");
    var validate = document.getElementById("dateRangeValidate");

    start_date.addEventListener("change", function() {
        var selectedDate = new Date(start_date.value);
        if (selectedDate !== null) {
            for (var i = 0; i < end_date.options.length; i++) {
                var option = end_date.options[i];
                var optionDate = new Date(option.value);
                if (optionDate < selectedDate) {
                console.log(option);
                option.setAttribute("disabled", "true");
                option.setAttribute("hidden", "true");
                } else {
                option.removeAttribute("disabled");
                option.removeAttribute("hidden");
                }
            }
        }
    });

    end_date.addEventListener("change", function() {
        var selectedDate = new Date(end_date.value);
        if (selectedDate !== null) {
            for (var i = 0; i < start_date.options.length; i++) {
                var option = start_date.options[i];
                var optionDate = new Date(option.value);
                if (optionDate > selectedDate) {
                    console.log(option);
                    option.setAttribute("disabled", "true");
                    option.setAttribute("hidden", "true");
                } else {
                    option.removeAttribute("disabled");
                    option.removeAttribute("hidden");
                }
            }
        }
    });
}
  
  dateRange();*/