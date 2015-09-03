  <?php require("../common_html/header.php");?>
  <link rel="stylesheet" type="text/css" href="../public/css/mem_body_result.css">
   <link rel="stylesheet" type="text/css" href="../public/css/mem_body_ft.css">
  <div id="con_wrap">
    <div class="input_002">足球赛程</div>
    <div class="con_menu">
      <form name="game_result" action="" method="post" id="game_result">
      <a href="football.php" target="_self">足球</a> 
      <a href="result_lq.htm" target="_self">籃球</a> 
      <a href="result_wq.htm" target="_self">網球</a> 
      <a href="result_pq.htm" target="_self">排球</a> 
      <a href="/app/other_set/result.php?uid=0e77cb7b025b3d4fd7f5273ca4bd&amp;game_type=BS&amp;layer=corp"
      target="_self">棒球</a> 
      <a href="/app/other_set/result.php?uid=0e77cb7b025b3d4fd7f5273ca4bd&amp;game_type=OP&amp;layer=corp"
      target="_self">其他</a> 
      <select name="selgtype" id="selgtype">
        <option value="FT" selected="selected">足球 : 赛果</option>
        <option value="NFS_FT">足球冠军 : 赛果</option>
      </select> 
      <input type="hidden" name="game_type" value="FT" /> 
      <input type="hidden" name="langx" value="zh-tw" />  
      <a href="/app/other_set/result.php?&amp;uid=0e77cb7b025b3d4fd7f5273ca4bd&amp;langx=zh-tw&amp;list_date=&amp;today=2014-11-28&amp;game_type=FT">昨日</a>
      
      <a href="/app/other_set/result.php?&amp;uid=0e77cb7b025b3d4fd7f5273ca4bd&amp;langx=zh-tw&amp;list_date=&amp;today=2014-11-30&amp;game_type=FT">
      明日</a>  选择日期 : 
      <input id="today_gmt" type="text" name="today" value="2014-11-29" size="9" maxlength="10" class="txt" /> 
      <input type="hidden" name="list_date" value="" /> 
      <input type="submit" class="za_button" value="查询" name="submit" style="height:auto" /> 
      <span id="pg_txt"></span></form>
    </div>
  </div>
  <div class="content" id="MRSU">
    <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tbody>
        <tr>
          <td>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>
                <tr>
                  <th class="time">时间</th>
                  <th class="rsu">赛果</th>
                </tr>
              </tbody>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>

                <tr>
                  <td colspan="6" class="b_hline">
                  <span id="S_108_1817926" class="showleg">
                    <span id="LegOpen"></span>
                  </span> 
                  <span class="leg_bar">香港高級組銀牌賽</span></td>
                </tr>
                <tr class="b_cen" id="TR_108_1817926" style="display:">
                  <td rowspan="3" class="time">11-29
                  <br />02:30a</td>
                  <td class="team">比賽隊伍</td>
                  <td colspan="2" class="team_out_ft">
                    <table border="0" cellpadding="0" cellspacing="0" class="team_main">
                      <tbody>
                        <tr class="b_cen">
                          <td width="12"></td>
                          <td class="team_c_ft">太陽飛馬足球會</td>
                          <td class="vs">vs.</td>
                          <td class="team_h_ft">黃大仙區康樂體育會</td>
                          <td width="12"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="more_td"></td>
                </tr>
                <tr id="TR_1_108_1817926" style="display:" class="hr">
                  <td class="hr_title">半場</td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;">1</span>
                  </td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;">0</span>
                  </td>
                  <td rowspan="2" class="more_td"></td>
                </tr>
                <tr id="TR_2_108_1817926" style="display:" class="full">
                  <td class="full_title">全場</td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;">1</span>
                  </td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;">3</span>
                  </td>
                </tr>
              </tbody>
            </table>
            <table id="game_table" cellspacing="0" cellpadding="0" class="game" style="display:none">
              <tbody>
                <tr>
                  <td colspan="20" class="no_game">
                  您選擇的項目暫時沒有賽事。請修改您的選項或遲些再返回。</td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </body>
</html>