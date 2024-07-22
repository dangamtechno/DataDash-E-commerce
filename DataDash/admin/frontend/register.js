
function createRegisterLink(){
    const formDiv = document.querySelector('.form-div');
    const register = document.createElement('button');
    register.className  = 'register-link';
    formDiv.appendChild(register);
    register.innerHTML = "Dont have account click here";
    register.addEventListener('click',registerUser);
}

function validateRegisterForm(){
    let input =  document.querySelectorAll('.registration-form input');
    let flag = false;
    const flagged_inputs = [];
    input.forEach(field=>{
        let input_value = field.value;
        //check all fields excpet for submit
        //input must be above 4 characters
        if(input_value.length < 4 && field.type != 'submit'){
            flagged_inputs.push(field);
            flag = true;
        }
    })
    if(flag){
        flagged_inputs.forEach(input=>{
            //console.log(`field: ${input.name} value: ${input.value}`);
        })
    }
    return flag;
}


function registerUser(){
    fetchCall('register.php',responseRegister);
    function responseRegister(data){
        const formDiv = document.createElement('div');
        formDiv.className = 'form-div';
        const formTitle = document.createElement('h2');
        formTitle.textContent = 'Admin Registration';
        formDiv.append(formTitle);
        const form = document.createElement('form');
        form.className = 'registration-form';
        console.log(data);
        if(data.columns){
            console.log(data.columns);
            const columns = data.columns;

        columns.forEach(column => {
            const input = document.createElement('input');
            const label = document.createElement('label');
            label.textContent = column.Field;
            input.name = column.Field;
            input.placeholder = column.Field;
            switch(column.Field){
            case 'password': input.type = 'password';
                break;
            case 'email': input.type = 'email';
                break;
            default: input.type = 'text';
                break;
            }
            console.log(`${input.name}`);
            form.appendChild(label);
            form.appendChild(input);
        });    
        const submit = document.createElement('input');
        submit.type = 'submit';
        submit.name = 'register';
        submit.addEventListener('click',submitRegistration);
        form.appendChild(submit);
        formDiv.appendChild(form);
        displayOverlay(formDiv);
    }

    }
}
function submitRegistration(e){
    e.preventDefault(); 
    let checkFormStatus = validateRegisterForm();
    if(checkFormStatus){
        alert('missing values'); 
        return;
    }
    const form = document.querySelector('.registration-form');
    const formData = new FormData(form);
    fetchCall('register.php',responseSubmitRegister,'POST',formData);
    function responseSubmitRegister(data){
        console.log(data);
        if(data.admin_registration){
            removeOverlay();
            alert('Registered successufully');
        }
        else{
            alert('registered unsuccessufully');
        }
    }
}