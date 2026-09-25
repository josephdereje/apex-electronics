from django.urls import path
from . import views

urlpatterns = [
    path("", views.home, name="home"),
    path("products/", views.products, name="products"),
    path("products/<slug:slug>/", views.product_detail, name="product_detail"),
    path("oem-odm/", views.oem, name="oem"),
    path("about/", views.about, name="about"),
    path("contact/", views.contact, name="contact"),
    path("api/products/", views.api_products, name="api_products"),
    path("quick-inquiry/", views.quick_inquiry, name="quick_inquiry"),
]
