
var feild = document.querySelector('input');
var backUp = feild.getAttribute('placeholder');
var btn = document.querySelector('.btn');


feild.onfocus = function(){
    this.setAttribute('placeholder', '');
    
}

feild.onblur = function(){
    this.setAttribute('placeholder',backUp);
    this.style.borderColor = '#aaa'
}

var feild = document.querySelector('textarea');
var backUp = feild.getAttribute('placeholder');
var btn = document.querySelector('.btn');


feild.onfocus = function(){
    this.setAttribute('placeholder', '');
    
}

feild.onblur = function(){
    this.setAttribute('placeholder',backUp);
    this.style.borderColor = '#aaa'
}