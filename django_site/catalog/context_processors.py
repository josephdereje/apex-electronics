from django.templatetags.static import static

from .forms import InquiryForm
from .models import Category, Inquiry


def site_defaults(request):
    return {
        "nav_categories": Category.objects.all(),
        "whatsapp_url": "https://wa.me/8613640666344",
        "website_url": "https://www.apextechnology.com",
        "catalog_url": static("docs/APEX-Company-Catalog.pdf"),
        "facebook_url": "https://www.facebook.com/apextechnology",
        "instagram_url": "https://www.instagram.com/apextechnology",
        "linkedin_url": "https://www.linkedin.com/company/apex-import-and-export",
        "email_url": "mailto:info@apextechnology.com",
        "current_year": __import__("datetime").date.today().year,
        "side_inquiry_form": InquiryForm(),
        "side_interest_choices": Inquiry.INTEREST_CHOICES,
    }
