<?php
error_reporting(E_ALL ^ E_NOTICE);
defined('BASEPATH') OR exit('No direct script access allowed');



class Student_test extends CI_Controller {
    
	/*public function index(){
		$this->load->view('student/stu_test.html');
	}
*/

	/*考试注意事项*/
	public function inf()
	{
		$this->load->library('session');
        $id=$this->session->userdata('user');
        $this->load->model('p_student','p_student');
        $res=$this->p_student->check_ans($id);
		if($res) error("你已完成测试！请耐心等待成绩。");
		else $this->load->view('student/inf.html');
	}

	/*加载答卷*/
	public function test(){
 		$this->load->library('session');
        $id=$this->session->userdata('user');
        // var_dump($id);
		$this->load->model('p_student','p_student');
		$res=$this->p_student->check_ans($id);
		if($res) error("你已完成测试！请耐心等待成绩。");
		$paper['inf']=$this->p_student->stu_test($id);
		//var_dump($paper);
		if($paper['inf']){
		$p_id=$paper['inf'][0]['paper_id'];
		$this->p_student->ans_flag($id,$p_id);
		$res=$this->p_student->get_sub($p_id);
		$s_id=explode(',', $res,-1);
		//var_dump($s_id);
		$length=sizeof($s_id);
		for($i=0;$i<$length;$i++)
		{
			$res1=$this->p_student->create($s_id[$i]);
			$res2[$i]=$res1;
		}
		$data['test']=$res2;
		//var_dump($data);
		
		$this->load->view('student/stu_test.html',$data);
		}
		else error("请在个人信息页完善你要加入的方向后再进行测试！");
		

	}
	/*提交答案*/
	public function stu_ans(){
		$this->load->library('session');
        $id=$this->session->userdata('user');
		$ans=file_get_contents("php://input");
		$a=json_decode($ans,TRUE);
		foreach($a as $k=>$v){
			foreach($v as $key=>$val){
				$arr[$key]=$val;
			}
		}
		$data=implode(',', $arr);
		$ans=$data.',';
		//echo $ans;
		$this->load->model('p_student','p_student');
		$res=$this->p_student->ans_add($ans,$id);
		if($res) echo "Success";
			else echo "Error";
	//	var_dump($ans);


	}

}