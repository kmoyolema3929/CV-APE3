class suma {
    constructor(num1, num2) {
        this.num1 = num1;
        this.num2 = num2;
    }    

    sumita() {
        return this.num1 + this.num2;
    }
}


function sumar (){
				var num1=parseInt(document.getElementById("txtnum1").value);
				var num2=parseInt(document.getElementById("txtnum2").value);
				var op1 = new suma(num1, num2);
				
                alert(op1.sumita());
                document.getElementById("resultado").innerText=op1.sumita();

}

class resta {
    constructor(num1, num2) {
        this.num1 = num1;
        this.num2 = num2;
    }    

    restita() {
        return this.num1 - this.num2;
    }
}


function restar (){
				var num1=parseInt(document.getElementById("txtnum1").value);
				var num2=parseInt(document.getElementById("txtnum2").value);
				var op1 = new resta(num1, num2);
				
                alert(op1.restita());
                document.getElementById("resultado").innerText=op1.restita();

}