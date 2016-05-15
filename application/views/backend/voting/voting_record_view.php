<form role="search" action="">
    <article class="well">
        <div class="btn-group">
            <a class="btn  btn-sm btn-purple" href="<?php echo bUrl('index',FALSE);?>">
                <i class="icon-edit bigger-120"></i>返回
            </a>
        </div>

        <hr>
        <h2>意見調查主題:<?php echo $list['subject'];?></h2>
        <div><?php echo $list['description'];?></div>
        <div>議題調查活動日期：<?php echo $list['start_date']." ~ ".$list['end_date']?></div>
    </article>
    
</form>
<form action="" id="update_form" method="post" class="contentForm">
    <div class="row">
        <div class="col-xs-12">
            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width:100px">序號</th>
                        <th>選項</th>
                        <th style="width:200px"><i class="icon-time bigger-110 hidden-480"></i>投票人數</th>
                       
                     
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0;$i<count($list['options']);$i++): ?>
                    <tr>
                        <td>
                           <?php echo $i+1;?>
                        </td>
                        <td>
                            <?php echo $list['options'][$i]["option_text"];?>
                        </td>
                        <td>
                            <?php echo $list['options'][$i]["voting_count"];?>
                        </td>                       
                       
                    </tr>
                    <tr>
                        <td colspan="3">
                              <?php
                                if($list['allow_anony']==1){
                                    echo "不記名投票";
                                }else{
                                    $users = $list['options'][$i]['user'];
                                    $str_users = '';
                                    for( $j=0; $j<count($users);$j++){
                                          $str_users.=$users[$j]['name'].",";
                                    }

                                    echo trim($str_users,",");

                                }

                           ?>    
                        

                        </td>
                    </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="3">
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

