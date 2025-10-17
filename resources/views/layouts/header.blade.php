 <!-- Top Bar -->
 <div class="d-flex justify-content-between align-items-center topbar mb-2">
     <div class="d-flex">
         <button class="btn btn-primary d-md-none m-2" onclick="toggleSidebar()">
             <i class="bi bi-list"></i> Menu
         </button>
         <script>
             function toggleSidebar() {
                 document.getElementById("sidebar").classList.toggle("show");
             }
         </script>

     </div>

     <!-- Notifications + User -->
     <div class="d-flex align-items-end gap-3 justify-content-center  gap-3">
         <div>
             <p>{{ Auth::user()->email ?? 'guest' }}</p>
         </div>
         <button class="btn btn-light position-relative me-2">
             <i class="bi bi-bell fs-5"></i>
         </button>
         <img src="{{ asset('images/user.png') }}" class="rounded-circle" width="40" alt="User">
     </div>
 </div>