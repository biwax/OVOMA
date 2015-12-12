//<![CDATA[

// test case for http://stackoverflow.com/questions/9464555/js-jquery-animated-random-name-picker

var students = [
    { 'Student_ID': 0, 'Student_Name': "Sports Café" },
    { 'Student_ID': 1, 'Student_Name': "San Marco" },
    { 'Student_ID': 2, 'Student_Name': "Pinocchio" },
    { 'Student_ID': 3, 'Student_Name': "Boccalino" },
    { 'Student_ID': 4, 'Student_Name': "Pirate" },
    { 'Student_ID': 5, 'Student_Name': "Milan" },
    { 'Student_ID': 6, 'Student_Name': "Café de l'Europe" },
    { 'Student_ID': 7, 'Student_Name': "White Horse" },
    { 'Student_ID': 8, 'Student_Name': "Florida" },
    { 'Student_ID': 9, 'Student_Name': "La Jonque" },
    { 'Student_ID': 10, 'Student_Name': "Carrousel Burger" },
    { 'Student_ID': 11, 'Student_Name': "Aux Bonnes Choses" },
    { 'Student_ID': 12, 'Student_Name': "Fleur de pains" },
    { 'Student_ID': 13, 'Student_Name': "Coop" },
    { 'Student_ID': 14, 'Student_Name': "Migros" },
];

var $display = $("#randomChoice");

$('#random').click(function(){
    var total = students.length,
        selected = Math.floor( Math.random() * total ),
        i = 0;

    console.log( "selected", selected );
    //$display.animate( {"font-size": "12px"}, 0 );
    // or
    $display.removeClass( "winner" );

    // improvement: use a for loop instead of a for..in
    for (i=0; i<total; i++) {

        console.log( "for", i );
        // here is the trick, use an Immediately-Invoked Function Expression (IIFE)
        // see http://benalman.com/news/2010/11/immediately-invoked-function-expression/
        // play with the two button "IIFE" and "No IIFE" to see why this is important
        setTimeout((function(i){
            return function(){
                // code here will be delayed
                console.log( "timeout", i );
                $display.text( students[i].Student_Name.toUpperCase() );
                if( i === selected ) {
                    //$display.animate( {"font-size": "30px"}, "fast" );
                    // or
                    $display.addClass( "winner" );
                }
            };
        }(i)), i*250);

        // improvement: triple equal sign, always !
        if( i === selected ) {
            // code here will execute immediately
            break;
        }
    }

});



$("#iife").click(function() {
    var i = 0, total = 10;

    for (i=0; i<total; i++) {
        // the only difference is here
        setTimeout((function(i){
            return function() {
                // this will log all values from 0 up to 9
                console.log( "timeout without IIFE", i );
            };
        }(i)), i*250);
    }

});

$("#no-iife").click(function() {
    var i = 0, total = 10;

    for (i=0; i<total; i++) {
        // the only real difference is in this function definition
        setTimeout(function() {
            // this will log the value 0 ten times
            console.log( "timeout without IIFE", i );
        }, i*250);
    }

});
//]]> 