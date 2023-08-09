$(".addphoto").click(function(){
    $("#postphoto").click();
});


//GET POSTS
$("#getposts").click(function(){
    
    var postoffset = $("#postoffset").val();
    var postspp = 2;


    var xmlhttp;
    xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState < 4){
            $("#getpostsword").css("display", "none");
            $("#getpostsspinner").css("display", "inline-block");
        }
        if(xmlhttp.readyState == 4 & xmlhttp.status == 200){
            postdata = xmlhttp.responseText;

            $("#posts").append(postdata);

            $("#getpostsspinner").css("display", "none");
            $("#getpostsword").css("display", "inline-block");
        }
    }

    xmlhttp.open("GET", "inc/posts.php?postoffset="+postoffset, true);
    xmlhttp.send();

    var newoffset = Number(postoffset)+postspp;
    document.getElementById("postoffset").value = newoffset;
});


//LIKE POST FUNCTION
$("body").on("click", ".post .like-btn", function(){
    var post_id = $(this).parent ().data ("id");

    // ajax code
    var xmlhttp2;
    xmlhttp2 = new XMLHttpRequest();

    var displaylike = "displaylike"+post_id; 

    xmlhttp2.onreadystatechange = function(){
        if(xmlhttp2.readyState == 4 & xmlhttp2.status == 200){
            document.getElementById(displaylike).innerHTML = xmlhttp2.responseText;
        }
    }

    xmlhttp2.open("GET", "inc/ajax.php?post="+post_id, true);
    xmlhttp2.send();
});