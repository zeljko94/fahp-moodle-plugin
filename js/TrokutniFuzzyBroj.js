
function TrokutniFuzzyBroj(v1,v2,v3)
{
    let self = this;
    this.v1 = parseFloat(v1);
    this.v2 = parseFloat(v2);
    this.v3 = parseFloat(v3);

    this.zbroji = function(tfb)
    {
        var rez = new TrokutniFuzzyBroj(this.v1+tfb.v1, this.v2+tfb.v2, this.v3+tfb.v2);
        return rez;
    }

    this.podijeliSa = function(n)
    {
        var rez = new TrokutniFuzzyBroj(this.v1/n, this.v2/n, this.v3/n);
        return rez;
    }

    self.inverz = function()
    {
        var v1 = 1 / this.v3;
        var v2 = 1 / this.v2;
        var v3 = 1 / this.v1;
        var tfb = new TrokutniFuzzyBroj(Math.round(v1*100) / 100, Math.round(v2*100)/100, Math.round(v3*100)/100);
        return tfb;
    }

    this.fromString = function(str)
    {
        str = str.replace("(", "");
        str = str.replace(")", "");
        var arr = str.split(",");

        var rez = new TrokutniFuzzyBroj(arr[0],arr[1],arr[2]);
        return rez;
    }

    this.toString = function()
    {
        var rez = "(" + this.v1 + ", " + this.v2 + ", " + this.v3 + ")";
        return rez;
    }
}

var TrokutniFuzzyBrojEnum = Object.freeze({U_potpunosti_jednako:  {vrijednost: new TrokutniFuzzyBroj(1,1,1),         toString: "U potpunosti jednako"},
    Jednako_važno:         {vrijednost: new TrokutniFuzzyBroj(0.5,1,1.66),    toString: "Jednako važno"},
    Umjereno_važnije: 	  {vrijednost: new TrokutniFuzzyBroj(1,1.5,2),       toString: "Umjereno važnije"},
    Dosta_važnije: 	      {vrijednost: new TrokutniFuzzyBroj(1.5,2,2.5),	 toString: "Dosta važnije"},
    Jako_važnije: 		  {vrijednost: new TrokutniFuzzyBroj(2,2.5,3),		 toString: "Jako važnije"},
    Izrazito_važnije:      {vrijednost: new TrokutniFuzzyBroj(2.5, 3, 3.5),   toString: "Izrazito važnije"}});
var TrokutniFuzzyBrojEnumDict = {};
TrokutniFuzzyBrojEnumDict[0] = TrokutniFuzzyBrojEnum.U_potpunosti_jednako;
TrokutniFuzzyBrojEnumDict[1] = TrokutniFuzzyBrojEnum.Jednako_važno;
TrokutniFuzzyBrojEnumDict[2] = TrokutniFuzzyBrojEnum.Umjereno_važnije;
TrokutniFuzzyBrojEnumDict[3] = TrokutniFuzzyBrojEnum.Dosta_važnije;
TrokutniFuzzyBrojEnumDict[4] = TrokutniFuzzyBrojEnum.Jako_važnije;
TrokutniFuzzyBrojEnumDict[5] = TrokutniFuzzyBrojEnum.Izrazito_važnije;
