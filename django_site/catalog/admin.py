from django.contrib import admin
from .models import Category, Inquiry, Product


@admin.register(Category)
class CategoryAdmin(admin.ModelAdmin):
    list_display = ("name", "slug", "sort_order")
    prepopulated_fields = {"slug": ("name",)}


@admin.register(Product)
class ProductAdmin(admin.ModelAdmin):
    list_display = ("name", "category", "sku", "is_featured", "is_active", "updated_at")
    list_filter = ("category", "is_featured", "is_active")
    search_fields = ("name", "sku", "short_description")
    prepopulated_fields = {"slug": ("name",)}


@admin.register(Inquiry)
class InquiryAdmin(admin.ModelAdmin):
    list_display = ("name", "email", "product_interest", "status", "created_at")
    list_filter = ("status", "product_interest")
    search_fields = ("name", "email", "message")
