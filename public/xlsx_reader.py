import zipfile
import xml.etree.ElementTree as ET
import sys
import json
import os

def read_xlsx(file_path):
    if not os.path.exists(file_path):
        return {"error": "File not found"}

    try:
        with zipfile.ZipFile(file_path, 'r') as z:
            # Load Shared Strings
            shared_strings = []
            if 'xl/sharedStrings.xml' in z.namelist():
                with z.open('xl/sharedStrings.xml') as f:
                    tree = ET.parse(f)
                    root = tree.getroot()
                    # Namespace map often needed, but simple iteration works for simple files
                    # Schema often: {http://schemas.openxmlformats.org/spreadsheetml/2006/main}sst
                    for t in root.iter():
                        if t.tag.endswith('t'):
                            shared_strings.append(t.text)

            # Load Sheet 1
            data = []
            with z.open('xl/worksheets/sheet1.xml') as f:
                tree = ET.parse(f)
                root = tree.getroot()
                # Find sheetData
                rows = []
                for row in root.iter():
                    if row.tag.endswith('row'):
                        r_data = []
                        for cell in row:
                            # cell.v is value, cell.t is type (s=shared string)
                            val = None
                            c_type = cell.get('t')
                            
                            # Find <v> child
                            v_node = None
                            for child in cell:
                                if child.tag.endswith('v'):
                                    v_node = child
                                    break
                            
                            if v_node is not None:
                                val = v_node.text
                                if c_type == 's':
                                    val = shared_strings[int(val)]
                            
                            r_data.append(val)
                        rows.append(r_data)
                
                # Filter empty rows
                data = [r for r in rows if any(r)]
                
        return {"data": data}

    except Exception as e:
        return {"error": str(e)}

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "No file provided"}))
    else:
        res = read_xlsx(sys.argv[1])
        print(json.dumps(res))
