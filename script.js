const backendURL = "backend.php";

function signup() {
  const name = sname.value, email = semail.value, password = spass.value;
  fetch(backendURL, {
    method: "POST",
    body: new URLSearchParams({ action: "signup", name, email, password })
  })
  .then(r => r.text())
  .then(t => alert(t));
}

function login() {
  const email = lemail.value, password = lpass.value;
  fetch(backendURL, {
    method: "POST",
    body: new URLSearchParams({ action: "login", email, password })
  })
  .then(r => r.json())
  .then(res => {
    if (res.status === "success") {
      username.innerText = res.name;
      authSection.style.display = "none";
      feedSection.style.display = "block";
      loadPosts();
    } else alert("Login failed!");
  });
}

function createPost() {
  const content = postText.value;
  fetch(backendURL, {
    method: "POST",
    body: new URLSearchParams({ action: "create_post", content })
  })
  .then(r => r.text())
  .then(t => {
    if (t === "success") {
      postText.value = "";
      loadPosts();
    }
  });
}

function loadPosts() {
  fetch(backendURL + "?action=get_posts")
  .then(r => r.json())
  .then(posts => {
    feed.innerHTML = "";
    posts.forEach(p => {
      feed.innerHTML += `<div class="post"><b>${p.name}</b><br>${p.content}<br><small>${p.created_at}</small></div>`;
    });
  });
}

function logout() {
  fetch(backendURL + "?action=logout")
  .then(() => location.reload());
}
