<style>
    /* Adjust the overall layout of the dashboard */
body {
    font-size: 0.875rem;
}

.sidebar-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    height: 100vh; /* Full height */
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable sidebar */
}

.nav-link.active {
    font-weight: bold;
}

.main-content {
    padding: 20px;
}

.dashboard-content h1 {
    font-size: 24px;
    margin-bottom: 20px;
}

.card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.bg-white {
    background-color: #fff !important;
}

.sidebar-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.sidebar-card .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sidebar-card .nav-link {
    color: black !important; 
}

</style>
