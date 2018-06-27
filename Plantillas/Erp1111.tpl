{$k = 0}
{$nTotal = 0}
{foreach from = $saDatos item = i}
   <tr>
      <th scope="row">{$k+1}</th>
      <td class="text-left" data-toggle="tooltip" data-placement="top" title="{$i['CDESDET']}">{$i['CDESART']}</td>
      <td>{$i['CUNIDAD']}</td>
      <td class="text-right">{number_format($i['NCANTID'],4)}</td>
      <td class="text-right">{number_format($i['NPREREF'],4)}</td>
      <td class="text-right">{number_format($i['NSTOTAL'],2)}</td>
      <td align="center"><input id="pcIndice" type="radio" name="pnIndice" value="{$k}"></td>
   </tr>
   {$k = $k + 1}
   {$nTotal = $nTotal + $i['NSTOTAL']}
{/foreach}
<tr>
   <td></td>
   <td colspan="4" class="text-left">TOTAL</td>
   <td align="right"><input type="hidden" id="pnTotal" name="paData[NTOTAL]" value="{number_format($nTotal,2)}">{number_format($nTotal,2)}</td>
   <td></td>
</tr>