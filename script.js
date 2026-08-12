// Ganti dengan URL & anon key dari Supabase kamu
const supabaseUrl = "https://YOUR_PROJECT_URL.supabase.co";
const supabaseKey = "YOUR_ANON_KEY";
const supabase = supabase.createClient(supabaseUrl, supabaseKey);

async function loadUsers() {
  const { data, error } = await supabase.from("users").select("*");
  if (error) {
    console.error("Error:", error);
    return;
  }

  const tbody = document.getElementById("users-data");
  tbody.innerHTML = "";
  data.forEach(user => {
    const row = `<tr><td>${user.id}</td><td>${user.name}</td><td>${user.email}</td></tr>`;
    tbody.innerHTML += row;
  });
}

loadUsers();
