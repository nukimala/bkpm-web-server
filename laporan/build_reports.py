import os
import docx
from docx.shared import Inches, Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.style import WD_STYLE_TYPE
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import qn, nsdecls

def create_tree(root_dir, prefix=""):
    """Generate ASCII tree for directory."""
    lines = []
    items = sorted(os.listdir(root_dir))
    # Filter out hidden or unnecessary files if needed, but keep structure clean
    items = [item for item in items if not item.startswith('.git') and item != 'tests']
    
    for i, item in enumerate(items):
        is_last = (i == len(items) - 1)
        connector = "└── " if is_last else "├── "
        path = os.path.join(root_dir, item)
        lines.append(f"{prefix}{connector}{item}{'/' if os.path.isdir(path) else ''}")
        if os.path.isdir(path):
            sub_prefix = prefix + ("    " if is_last else "│   ")
            lines.extend(create_tree(path, sub_prefix))
    return lines

print("Helper defined.")
