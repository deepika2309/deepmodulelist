 <?php
class block_deepmodulelist extends block_base {
    public function init() {
        $this->title = get_string('deepmodulelist', 'block_deepmodulelist');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
    	global $CFG,$DB,$USER;
	    if ($this->content !== null) {
	      return $this->content;
	    }

	    $course = $this->page->course;
        $context = context_course::instance($course->id);
        $course = $DB->get_record('course', array('id' => $course->id));

        $record = '';
		$mods = get_course_mods($course->id);
		foreach($mods as $cm) {
			
		  // if (coursemodule_visible_for_user($cm, $USER->id)) {//no more supported
		  	if(core_availability\info_module::is_user_visible($cm, $USER->id))
		  	{
		  			// if($cm->modname != 'Forum')
		  			// {
			     $modrec = $DB->get_record($cm->modname, array('id' => $cm->instance));
			     $getcompletion = $DB->get_record('course_modules_completion', array('userid' => $USER->id,'coursemoduleid' => $cm->id),'completionstate');
			       // print_object($getcompletion);
			       // echo $getcompletion->completionstate;//die;
				     // echo "<BR>";
				     if($getcompletion->completionstate == 1)
			     	$completed = " - Completed";
			     else
			     	$completed = '';
			     $record .= "<a href='$CFG->wwwroot/mod/".$cm->modname."/view.php?id=".$cm->id."'>".$cm->id." - ". $modrec->name." - ".date("d-M-Y",$modrec->timemodified).$completed."</a>";
			     $record .= "<BR>";
			     //} 
		 	}
		  // }
		}


	    $this->content         =  new stdClass;
	    $this->content->text   = $record;
	    // $this->content->footer =  'Footer here...';
	 
	    return $this->content;
	}
	public function applicable_formats() {
  		return array('course-view' => true);//to show availability to add only in course page.
	}
}