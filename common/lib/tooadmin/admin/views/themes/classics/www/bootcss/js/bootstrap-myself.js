
function reSetTabIndex() {
$("#myTabLi1").removeClass("active");
$("#myTabDiv1").removeClass("tab-pane active");
$("#myTabDiv1").addClass("tab-pane");

$("#myTabLi"+tabIndex).addClass("active");
$("#myTabDiv"+tabIndex).removeClass("tab-pane");
$("#myTabDiv"+tabIndex).addClass("tab-pane active");
}