$('#appendDifferentFamilyCodeTable').append("<tr><td><i class='fa fa-minus-circle removeDevotee' aria-hidden='true'></i></td>" +
  "<td class='checkbox-col'><input type='checkbox' name='xiaozai_id[]' value='" + data.devotee_id + "' class='different xiaozai_id'>" +
  "<input type='hidden' class='form-control hidden_xiaozai_id' name='hidden_xiaozai_id[]'  value=''></td>" +
  "<td>" + data.chinese_name +"</td>" +
  "<td><input type='hidden' name='devotee_id[]' class='append-devotee-id' value='" + data.devotee_id + "'>" + data.devotee_id + "</td>" +
  "<td></td>" +
  "<td>" + $.trim(data.guiyi_name) + "</td>" +
  "<td></td>" +
  "<td>" + data.ops + "</td>" +
  "<td><select class='form-control' name='type[]' style='display: none;'><option value='" + data.type  + "' selected>" + data.type + "</option>" +
  "</select><span class='type'>" + data.chinese_type + "</span></td>" +
  "<td>" + data.item_description + "</td>" +
  "<td>" + $.trim(data.paytill_date) + "</td>" +
  "<td>" + (data.lasttransaction_at !=null ? data.lasttransaction_at : '') + "</td>");
