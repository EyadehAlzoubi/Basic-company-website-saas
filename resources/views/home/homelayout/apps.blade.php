@php
  $apps = App\Models\App::find(1);
@endphp
<section class="lonyo-cta-section bg-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="lonyo-cta-thumb" data-aos="fade-up" data-aos-duration="500">
            <img id="appImage" src="{{ asset($apps->image) }}" alt="" data-id="{{ $apps->id }}" style="cursor: pointer; width:100%; max-width:300px;">
            @if(auth()->check())
              <input type="file" id="uploadImage" accept="image/*" style="display:none">
            @endif
          </div>
        </div>
        <div class="col-lg-6">
          <div class="lonyo-default-content lonyo-cta-wrap" data-aos="fade-up" data-aos-duration="700">
            <h2 class="editable-title" contenteditable="{{ auth()->check() ? 'true' : 'false' }}" data-id="{{ $apps->id }}">{{ $apps->title }}</h2>
            <p class="editable-description" contenteditable="{{ auth()->check() ? 'true' : 'false' }}" data-id="{{ $apps->id }}">{{ $apps->description }}</p>
            <div class="lonyo-cta-info mt-50" data-aos="fade-up" data-aos-duration="900">
              <ul>
                <li>
                  <a href="https://www.apple.com/app-store/"><img src="{{ asset('frontend/assets/images/v1/app-store.svg') }}" alt=""></a>
                </li>
                <li>
                  <a href="https://playstore.com/"><img src="{{ asset('frontend/assets/images/v1/play-store.svg') }}" alt=""></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    function saveChanges(element) {
      if (!element.classList.contains("editable-title") && !element.classList.contains("editable-description")) {
        return;
      }

      const appId = element.dataset.id;
      const field = element.classList.contains("editable-title") ? "title" : "description";
      const newValue = element.innerText.trim();

      fetch(`/update-app/${appId}`, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
          "Accept": "application/json",
        },
        body: JSON.stringify({ [field]: newValue }),
      })
        .then(response => {
          if (!response.ok) {
            throw new Error(`Request failed (${response.status})`);
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            console.log(`${field} updated successfully`);
          }
        })
        .catch(error => console.error("Error:", error));
    }

    document.addEventListener("keydown", function (e) {
      if (e.key === "Enter" && e.target.matches(".editable-title, .editable-description")) {
        e.preventDefault();
        saveChanges(e.target);
        e.target.blur();
      }
    });

    document.querySelectorAll(".editable-title, .editable-description").forEach(el => {
      el.addEventListener("blur", function () {
        saveChanges(el);
      });
    });

    const imageElement = document.getElementById("appImage");
    const uploadInput = document.getElementById("uploadImage");

    if (imageElement && uploadInput) {
      imageElement.addEventListener("click", function () {
        uploadInput.click();
      });

      uploadInput.addEventListener("change", function () {
        const file = this.files[0];
        if (!file) return;

        const appId = imageElement.dataset.id;
        const formData = new FormData();
        formData.append("image", file);
        formData.append("_token", csrfToken);

        fetch(`/update-app-image/${appId}`, {
          method: "POST",
          headers: {
            "Accept": "application/json",
          },
          body: formData,
        })
          .then(response => {
            if (!response.ok) {
              throw new Error(`Request failed (${response.status})`);
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              imageElement.src = data.image_url;
              console.log("Image updated successfully");
            }
          })
          .catch(error => console.error("Error:", error));
      });
    }
  });
  </script>
