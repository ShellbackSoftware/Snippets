// Previous slide
$('.btn-slide-prev').on('click', function() {
    var prev_slide = $(this).data("id");
    var cur_slide = $(this).data("id")+1;
    // Turn off current slide
    $('#slide'+cur_slide).toggleClass('hide');

    // Turn on previous slide
    $('#slide'+prev_slide).toggleClass('hide');
  });

  // Next slide
  $('.btn-slide-next').on('click', function() {
    var next_slide = $(this).data().id;
    var cur_slide = $(this).data().id-1;
    // Turn off current slide
    $('#slide'+cur_slide).toggleClass('hide');

    // Turn on next slide
    $('#slide'+next_slide).toggleClass('hide');
  });

  // Jump to slide
  // TODO: Change to use with "Submit" button
  $('.jumpToSlide').on('change', function() {
    var cur_slide = $(this).data().parent;
    var target_slide = this.value;

    // Reset the dropdown
    $('#selectSlide'+cur_slide).get(0).selectedIndex = 0;

    // Turn off current slide
    $('#slide'+cur_slide).toggleClass('hide');

    // Turn on target slide
    $('#slide'+target_slide).toggleClass('hide');
  });