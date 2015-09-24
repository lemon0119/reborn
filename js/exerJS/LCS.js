/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function max(p1,p2) {
    return p1 > p2 ? p1 : p2;
}
function LCS(str1,str2){
    if(str1 == null || str2 == null)
        return null;
    //设置字符串长度
    this.substringLength1 = str1.length;
    this.substringLength2 = str2.length;
    this.subLCS = '';
    this.opt = Array([this.substringLength1]);
	for(var it = 0; it < this.substringLength1 + 1; ++it){
		this.opt[it] = new Array([this.substringLength2]);
		for(var jt = 0; jt < this.substringLength2 + 1; ++jt){
			this.opt[it][jt] = 0;
		}
	}
    this.x = str1;
    this.y = str2;
    this.x_m = "";
    this.y_m = "";
    //opt 没有初始化。
    this.doLCS = function(){
        // 动态规划计算所有子问题  
        for ( var i = this.substringLength1 - 1; i >= 0; i--){  
            for ( var j = this.substringLength2 - 1; j >= 0; j--){
                if (this.x[i] == this.y[j])  {
                    this.opt[i][j] = this.opt[i + 1][j + 1] + 1;
                }
                else {
                    this.opt[i][j] = max(this.opt[i + 1][j], this.opt[i][j + 1]);
                }
            }
        }
        i = 0;
        j = 0;
        while (i < this.substringLength1 && j < this.substringLength2){  
            if (this.x[i] === this.y[j]){
                this.subLCS += this.x[i];
                this.x_m += this.x[i];
                this.y_m += this.x[i];
                i++;
                j++;
            } else if (this.opt[i + 1][j] >= this.opt[i][j + 1])  {
		this.x_m += '*';
                i++;
            } else{
                this.y_m += '*';
                j++;
            }
        }
    };
    this.getSubString = function (type){
        switch (type) {
            case 1 :
                return this.x_m;
            case 2:
                return this.y_m;
            default:
                return this.subLCS;
        }
    };
    this.getStrOrg= function (type){
        switch (type) {
            case 1 :
                return this.x;
            default:
                return this.y;
        }
    };
}