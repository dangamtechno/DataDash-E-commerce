
function getBanners(){
    const adminButtons = document.querySelector('.admin-buttons');
    const banners    =   document.createElement('li');
    const bannersButton = document.createElement('button');
    bannersButton.textContent='EDIT BANNERS';
    bannersButton.className='model-button';
    banners.append(bannersButton);
    adminButtons.appendChild(banners);
    bannersButton.addEventListener('click',requestBanners);
}
function requestBanners(){
    fetchCall('banner.php',responseBanners);
    function responseBanners(data){
       const modal = document.createElement('div');
       console.log(data);
       var  banners = data.banners;
       //if banners is not empty
       if(banners.length == 0){ 
        const section_header= document.createElement('h2');
        section_header.innerText="No banners to edit\nCreate Banner Here";
        modal.appendChild(section_header);
        createNewBannerForm(modal);
        //create banner form here
       }
       else{

            for(const banner in banners){
                //create card to hold product info
                const card = document.createElement('div');
                card.className = "card";
                //image for product
                const imgDiv = document.createElement('div');
                imgDiv.className = "card-img";
                //product name price category and product desc
                const descDiv = document.createElement('div');
                descDiv.className = "card-desc";
                //apply it to card
                card.appendChild(imgDiv);
                card.appendChild(descDiv);
                //apply event when card click to get product details 
                card.addEventListener('click',getBannerDetails.bind(banners[banner]));
                //image element for product
                const img = document.createElement('img');
                img.src = `../../frontend/images/banner/${banners[banner].image}`;
                imgDiv.appendChild(img);
                //product name will be the name the for card
                const name = document.createElement("p");
                name.textContent = banners[banner].name;
                //append to description container
                descDiv.appendChild(name);
                modal.appendChild(card);
                //its empty display empty
                //add insert banner here
            }
       }
       displayOverlay(modal);
       //ADD BUTTON FOR ADD NEW ITEM HERE
    }
}

function getBannerDetails(){
    console.log(this.id);
    fetchCall(`banner.php?id=${this.id}`,responseBannerDetails.bind(this))
    function responseBannerDetails(data){
        //grab banner from response
        let banner = data.banner;
        const modal = document.createElement('div');
        modal.className= 'modal';
        modal.id = 'edit_banner';
        //header    for edit    banners
        const formHeader = document.createElement('h2');
        formHeader.textContent = "Update Banners"; 
        modal.appendChild(formHeader);
        //desc will be the name and descp of banner
        const descDiv = document.createElement('div');
        descDiv.className = 'desc-div';
        const desc = document.createElement('p');
        desc.id = 'description';
        desc.innerText = `Description: ${banner.description}`;
        //image section of banner
        const modalImage = document.createElement('div');
        modalImage.className='card-img';
        const img = document.createElement('img')
        img.src = `../../frontend/images/banner/${banner.image}`;
        //name of banner
        const name = document.createElement('p');
        name.id = 'name';
        name.innerText = `Name: ${banner.name}`;
        //status of banner
        const status = document.createElement('p');
        status.id = 'status';
        status.innerText = `status: ${getStatus(banner.status)}`;
     //append to desc div
        descDiv.appendChild(name);
        descDiv.appendChild(desc);
        descDiv.appendChild(status);
    //append to modal image dive
        modalImage.appendChild(img);
        modal.appendChild(modalImage);
        modal.appendChild(descDiv);        
    //remove current overlay
     removeOverlay();
    //display new overlay
        displayOverlay(modal);
    //add update form to modal    
        updateBannerForm(modal,this);
    }
}

function createNewBannerForm(modal){
   const formHeader = document.createElement('h2');
        formHeader.textContent = "Create BANNER"; 
        const formDiv = document.createElement('div');
        formDiv.appendChild(formHeader);
        const form = document.createElement('form');
        form.className = 'create-banner';
        formDiv.className = 'form-div';
        //name
        const nameField= document.createElement('input');
        nameField.type = 'text';
        nameField.placeholder='name';
        nameField.name='name';
        //desc
        const descField = document.createElement('input');
        descField.type= 'text';
        descField.placeholder = 'description';
        descField.name = 'description';
        //image
        const imageField= document.createElement('input');
        imageField.type = 'text';
        imageField.placeholder='image src';
        imageField.name='image';
        imageField.id ='image'
        //status is either 1 or 0
        const statusSection = document.createElement('div');
        const statusSectionHeader = document.createElement('h3');
        statusSectionHeader.innerHTML = 'Status';
        statusSection.appendChild(statusSectionHeader);
        const selectStatus = document.createElement('select');
        selectStatus.name = 'status';
        for(let i = 0; i <= 1;i++){
         let statusField =   document.createElement('option');
         statusField.type = '';
         statusField.value = i;
         statusField.innerText = getStatus(i);
         selectStatus.appendChild(statusField);
     }
     //submit will send info to be used to update value in database where id is the currently viewed object id
        const submit = document.createElement('input');
        submit.name='submit';
        submit.type='submit';
        //EVENTLISTENER FOR UPDATE TABLE
        submit.addEventListener('click',submitCreateBanner);
        //form.appendChild(statusSection);
        form.appendChild(nameField);
        form.appendChild(descField);
        form.appendChild(imageField);
        statusSection.appendChild(selectStatus);
        form.appendChild(statusSection);
        form.appendChild(submit);
        formDiv.appendChild(form);
        modal.appendChild(formDiv);
}
function updateBannerForm(modal,banner){
    //header of form
   const formHeader = document.createElement('h2');
   formHeader.textContent = "UPDATE BANNER"; 
   const formDiv = document.createElement('div');
   formDiv.appendChild(formHeader);
   const form = document.createElement('form');
   form.className = 'update-banner';
   formDiv.className = 'form-div';
   const nameField= document.createElement('input');

   nameField.type = 'text';
   nameField.placeholder='name';
   nameField.name='name';
   const descField = document.createElement('input');
   descField.type= 'text';
   descField.placeholder = 'description';
   descField.name = 'description';
   //image
   const imageField= document.createElement('input');
   imageField.type = 'text';
   imageField.placeholder='image src';
   imageField.name='image';
   imageField.id ='image'

   //status is either 1 or 0
   const statusSection = document.createElement('div');
   const statusSectionHeader = document.createElement('h3');
   statusSectionHeader.innerHTML = 'Status';
   statusSection.appendChild(statusSectionHeader);
   const selectStatus = document.createElement('select');
   selectStatus.name = 'status';
   for(let i = 0; i <= 1;i++){
    let statusField =   document.createElement('option');
    statusField.type = '';
    statusField.value = i;
    statusField.innerText = getStatus(i);
    selectStatus.appendChild(statusField);
   }
    //default values will be the current ones in db
   selectStatus.selectedIndex = banner.status;
   nameField.value = banner.name;
   descField.value = banner.description;
//submit will send info to be used to update value in database where id is the currently viewed object id
   const submit = document.createElement('input');
   submit.name='submit';
   submit.type='submit';
   //EVENTLISTENER FOR UPDATE TABLE
   submit.addEventListener('click',submitBannerUpdate.bind(banner));
   //form.appendChild(statusSection);
   form.appendChild(nameField);
   form.appendChild(descField);
   form.appendChild(imageField);
   statusSection.appendChild(selectStatus);
   form.appendChild(statusSection);
   form.appendChild(submit);
   formDiv.appendChild(form);
   modal.appendChild(formDiv);
}
//create
function submitCreateBanner(e){
    e.preventDefault();
    form = document.querySelector('.create-banner');
    const formData = new FormData(form);
    fetchCall('banner.php',responseCreateBanner,'POST',formData);
    function responseCreateBanner(data){
       console.log(data);
    }
}
//update
function submitBannerUpdate(e){
    e.preventDefault();
    form = document.querySelector('.update-banner');
    console.log(form);
    const formData = new FormData(form);
    formData.append('id',this.id);
    //formData.append('status',)
    fetchCall(`banner.php`,responseBannerUpdate,'POST',formData);
    function responseBannerUpdate(data){
        //update banner card
        if(data.banner){
            const banner = data.banner;
            const bannerName = document.getElementById('name');
            const bannerStatus = document.getElementById('status');
            const bannerDesc = document.getElementById('description');
            const newName = banner.name;
            const newStatus = banner.status;
            const newDesc = banner.description;
            bannerName.innerText = `Update Name: ${newName}`;
            bannerStatus.innerText = `Updated Status: ${getStatus(newStatus)}`;
            bannerDesc.innerText = `Updated Description: ${newDesc}`;
            alert("banner update successful");
        }
    }
    //console.log(form);
    //alert(this.id   +  formData );

}
//delete