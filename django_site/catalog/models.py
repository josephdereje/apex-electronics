from django.db import models
from django.utils.text import slugify


class Category(models.Model):
    name = models.CharField(max_length=120)
    slug = models.SlugField(unique=True)
    eyebrow = models.CharField(max_length=40, blank=True)
    summary = models.TextField()
    highlights = models.TextField(help_text="One highlight per line")
    sort_order = models.PositiveIntegerField(default=0)

    class Meta:
        ordering = ["sort_order", "name"]
        verbose_name_plural = "Categories"

    def __str__(self):
        return self.name

    @property
    def highlight_list(self):
        return [line.strip() for line in self.highlights.splitlines() if line.strip()]


class Product(models.Model):
    category = models.ForeignKey(Category, related_name="products", on_delete=models.CASCADE)
    name = models.CharField(max_length=180)
    slug = models.SlugField(unique=True, blank=True)
    sku = models.CharField(max_length=60, blank=True)
    short_description = models.CharField(max_length=240)
    description = models.TextField()
    specifications = models.TextField(blank=True, help_text="One specification per line")
    moq = models.CharField(max_length=80, blank=True)
    lead_time = models.CharField(max_length=80, blank=True)
    image = models.ImageField(upload_to="products/", blank=True)
    is_featured = models.BooleanField(default=False)
    is_active = models.BooleanField(default=True)
    created_at = models.DateTimeField(auto_now_add=True)
    updated_at = models.DateTimeField(auto_now=True)

    class Meta:
        ordering = ["-is_featured", "name"]

    def __str__(self):
        return self.name

    def save(self, *args, **kwargs):
        if not self.slug:
            base = slugify(self.name)
            slug = base
            index = 2
            while Product.objects.filter(slug=slug).exclude(pk=self.pk).exists():
                slug = f"{base}-{index}"
                index += 1
            self.slug = slug
        super().save(*args, **kwargs)

    @property
    def spec_list(self):
        return [line.strip() for line in self.specifications.splitlines() if line.strip()]


class Inquiry(models.Model):
    STATUS_CHOICES = [
        ("new", "New"),
        ("reviewing", "Reviewing"),
        ("quoted", "Quoted"),
        ("closed", "Closed"),
    ]
    INTEREST_CHOICES = [
        ("tempered-glass", "Tempered glass"),
        ("bluetooth-headphones", "Bluetooth headphones"),
        ("power-banks", "Power banks"),
        ("chargers-cables", "Chargers & cables"),
        ("mixed-container", "Multiple / mixed container"),
        ("custom-odm", "Custom ODM project"),
    ]

    name = models.CharField(max_length=120)
    email = models.EmailField()
    country = models.CharField(max_length=120, blank=True)
    product_interest = models.CharField(max_length=40, choices=INTEREST_CHOICES)
    quantity = models.CharField(max_length=80, blank=True)
    message = models.TextField()
    status = models.CharField(max_length=20, choices=STATUS_CHOICES, default="new")
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ["-created_at"]
        verbose_name_plural = "Inquiries"

    def __str__(self):
        return f"{self.name} · {self.get_product_interest_display()}"
