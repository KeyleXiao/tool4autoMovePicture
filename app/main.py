import tkinter as tk
from tkinter import ttk

class AutoMovePictureApp(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title("Auto Move Picture Tool")
        self.configure(bg="#f0f0f0")
        self._build_ui()

    def _build_ui(self):
        style = ttk.Style()
        style.theme_use('clam')
        style.configure('TFrame', background='#f0f0f0')
        style.configure('TLabel', background='#f0f0f0', font=('Segoe UI', 10))
        style.configure('TEntry', relief='flat')
        style.configure('TButton', relief='flat', background='#007acc', foreground='white')
        style.map('TButton', background=[('active', '#005f99')])

        main_frame = ttk.Frame(self, padding=20)
        main_frame.pack(fill='both', expand=True)

        path_label = ttk.Label(main_frame, text='Source Folder:')
        path_label.grid(row=0, column=0, sticky='w')

        self.path_entry = ttk.Entry(main_frame, width=40)
        self.path_entry.grid(row=0, column=1, padx=5, pady=5)

        browse_btn = ttk.Button(main_frame, text='Browse')
        browse_btn.grid(row=0, column=2, padx=(0,5))

        dest_label = ttk.Label(main_frame, text='Destination Folder:')
        dest_label.grid(row=1, column=0, sticky='w')

        self.dest_entry = ttk.Entry(main_frame, width=40)
        self.dest_entry.grid(row=1, column=1, padx=5, pady=5)

        browse_dest_btn = ttk.Button(main_frame, text='Browse')
        browse_dest_btn.grid(row=1, column=2, padx=(0,5))

        move_btn = ttk.Button(main_frame, text='Move Pictures', command=self.move_pictures)
        move_btn.grid(row=2, column=0, columnspan=3, pady=(10,0))

        for child in main_frame.winfo_children():
            child.grid_configure(pady=2)

    def move_pictures(self):
        print('Moving pictures...')

if __name__ == '__main__':
    app = AutoMovePictureApp()
    app.mainloop()
