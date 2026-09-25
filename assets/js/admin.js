(function () {
  const input = document.querySelector("#id_image, input[name='image']");
  const preview = document.querySelector("[data-image-preview]");
  if (!input || !preview) return;
  input.addEventListener("change", function () {
    const file = input.files && input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (event) {
      preview.src = event.target.result;
      preview.hidden = false;
    };
    reader.readAsDataURL(file);
  });
})();
