document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('job-form');
    const jobList = document.getElementById('job-list');

    if (form) {
        handleJobForm(form);
    } else if (jobList) {
        displayJobList(jobList);
    }
    
    console.log('Script cargado.');  // Comprobar si el script se carga
});

function handleJobForm(form) {
    console.log('Formulario encontrado.');  // Comprobar si el formulario se encuentra
    
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        console.log('Formulario enviado.');  // Comprobar si se envía el formulario
        
        const title = form.title.value;
        const company = form.company.value;
        const location = form.location.value;
        const description = form.description.value;
        
        console.log('Datos del formulario:', { title, company, location, description });  // Comprobar los datos del formulario
        
        // Guardar la oferta de trabajo en el almacenamiento local
        const job = { title, company, location, description };
        const jobs = JSON.parse(localStorage.getItem('jobs')) || [];
        jobs.unshift(job);
        localStorage.setItem('jobs', JSON.stringify(jobs));
        
        form.reset();
        window.location.href = 'index.html';
    });
}

function displayJobList(jobList) {
    console.log('Lista de trabajos encontrada.');  // Comprobar si la lista de trabajos se encuentra
    
    // Cargar las ofertas de trabajo desde el almacenamiento local
    const jobs = JSON.parse(localStorage.getItem('jobs')) || [];
    jobs.forEach(job => {
        const jobItem = document.createElement('div');
        jobItem.className = 'job-item';
        jobItem.innerHTML = `
            <h3>${job.title}</h3>
            <p><strong>Empresa:</strong> ${job.company}</p>
            <p><strong>Ubicación:</strong> ${job.location}</p>
            <p>${job.description}</p>
        `;
        jobList.appendChild(jobItem);
    });
}