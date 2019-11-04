<?php
	require_once('../vendor/autoload.php');
	use MarwaDB\Connection;
	use MarwaDB\DB;

	$config = require_once('conf.php');

	if(!$config['database'])
	{
		die('Database configuration not found');
	}

	$db = new DB($config['database']);

	$result = $db->rawQuery('SELECT * FROM system WHERE id = ?',[1]);
	dump("Total Rows Returned >>> ".$db->rows());
	dump($result);

	$result=$db->connection('sqlSrv')->rawQuery('SELECT * FROM system WHERE id = ?',[1]);
	dump("Result with Specific Connection Name >>> ".$result->username);

	$result=$db->connection()->rawQuery('SELECT * FROM system WHERE id = ?',[1]);
	dump("Result with Null Connection >>> ".$result->username);

	$result=$db->select('SELECT * FROM system');
	dump("Result without PlaceHolder >>> ".$result[0]->username);

	$result=$db->select('SELECT * FROM system WHERE id = ?',[1]);
	dump("Result with PlaceHolder >>> ".$result->username);

	//SELECT single row query
	$result = $db->rawQuery("SELECT * FROM system WHERE id = :id LIMIT 1", ['id' => '1']);
	dump("Single Row Result >> ".$result->username);

 //query to fetch all data
  $result = $db->table('system')->select("username as user")->get();
  dump($result);

  //get all results
	$result = $db->table('system')->select("*")->get();
	dump($result);

	//get all result
	$result = $db->table('system')->select()->get();
	dump($result);

	$result = $db->table('system')->select("username")->addSelect("system.password")->get();
	dump($result);
	//loop throgh result set
	foreach($result as $row )
	{
		dump("Username :".$row->username);
	}

	//query on Where condition
	$result=$db->table('system')->select()->whereRaw('id = ?',["1"])->get();
	dump($result);

	$result=$db->table('system')->select()->whereRaw('id = 1')->get();
	dump($result);

	//query for whereRaw and orWhereRaw Condition together
	$result=$db->table('system')->select()->whereRaw('id = ?',['1'])->orWhereRaw('id = ?',['127.1.1.1'])->get();
	dump($result);

	//query for whereRaw and andWhereRaw Condition
	$result=$db->table('system')->select()->whereRaw('id = ?',[1])->andWhereRaw('username = ?',['admin'])->get();
	dump($result);

  //where two column paramenter
	$users=$db->table("system")->select()->where("id","1")->get();
	dump($users);

  $sql=$db->table("system")->select()->orSubWhere('username = ? OR id = ?',['admin','0'])->get();
	dump($sql);
  die;

  //where two column paramenter
  $users=$db->table("system")->select()->whereLike("username","%admin%")->get();
  dump($users);


	//where two column paramenter
	$users = $db->table("system")->whereExists(function($db)
	{
		  return $db->table('rule')->select()->sqlString();
	})->get();
	dump($users);

	// //query for orderby, offset, limit, groupby
	$result = $db->table('system')->select()->orderBy('id','DESC')->offset(0)->limit(1)->groupBy('id')->get();
	dump($result);

	//query for havingRaw
	$result=$db->table('system')->select()->havingRaw('id = 1')->get();
	dump($result);

	//select Raw
	$result=$db->table('system')->select("username")->get();
	dump($result);

	//select column by choose
	$result=$db->table('system')->select("*")->get();
	dump($result);

	//select distinct
	$users = $db->table('system')->distinct("username")->get();
	dump($users);

	//inner join
	$users=$db->table("system")->select()->join("rule",'rule.id','=','system.type')->get();
	dump($users);

	//leftjoin Sql
	$users=$db->table("system")->select()->leftjoin("rule",'rule.id','=','system.type')->get();
	dump($users);

	//rightJoin
	$users=$db->table("system")->select()->rightjoin("rule",'rule.id','=','system.type')->get();
	dump($users);

	//whereBetween
	$users=$db->table("system")->select()->whereBetween('id',[0,10])->get();
	dump($users);

	$users=$db->table("system")->select()->whereBetween('id',[0,10])->orWhereBetween('id',[0,10])->get();
	dump($users);

	//where is NULL
	$users=$db->table("system")->select()->whereNull('id')->get();
	dump($users);

	//where is NOT NULL
	$users=$db->table("system")->select()->whereNotNull('id')->get();
	dump($users);

	//Count Total Rows
	$users=$db->table("system")->count('*','total');
	dump($users);
	//sum of column
	$users=$db->table("system")->sum('id','total');
	dump($users);

	//Wherein
	$users=$db->table("system")->select()->whereIn("id",[1,2])->get();
	dump($users);

	//WhereNotIn
	$users=$db->table("system")->select()->whereNotIn("id",[1])->get();
	dump($users);

	//WhereDate
	$users=$db->table("activecalls")->select()->whereDate("connecttime",'2019-06-17')->get();
	dump($users);

	//whereMonth
	$users=$db->table("activecalls")->select()->whereMonth("connecttime",'06')->get();
	dump($users);

	//insert and save thedata
	// $result=$db->table('rule')->insert(
	// 		[
	// 			['rtype' => 'Operator6', 'permission' => 'Administrator'],
	// 			['rtype' => 'Operator8', 'permission' => 'Administrator'],
	// 		]
	// )->save();
	// dump($result);

	//insert and retrieve last inserted id
 //    $result=$db->table('rule')->insertAndGetId(
	// 		[
	// 			['rtype' => 'Operator7', 'permission' => 'Administrator'],
	// 			['rtype' => 'Operator9', 'permission' => 'Administrator'],
	// 		]
	// );
	// dump($result);

	//update the data
	// $result = $db->table('rule')->update(
	// 			['rtype' => 'Operator1', 'permission' => 'Administrator']
	// )->where("id","117")->save();
  //
	// dump($result);

	// $result = $db->table('rule')->update(
	// 			['rtype' => 'Operator2', 'permission' => 'Administrator']
	// )->where("id","109")->save();

	// dump($result);

	// $result = $db->table('rule')->delete()->where("id","118")->save();
	// dump($result);
	// die;


?>
