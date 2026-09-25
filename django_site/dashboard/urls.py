from django.urls import path
from . import views

urlpatterns = [
    path("login/", views.admin_login, name="admin_login"),
    path("logout/", views.admin_logout, name="admin_logout"),
    path("", views.dashboard_home, name="dashboard_home"),
    path("products/", views.product_list, name="dashboard_products"),
    path("products/new/", views.product_create, name="dashboard_product_create"),
    path("products/<int:pk>/edit/", views.product_edit, name="dashboard_product_edit"),
    path("products/<int:pk>/delete/", views.product_delete, name="dashboard_product_delete"),
    path("inquiries/", views.inquiry_list, name="dashboard_inquiries"),
]
