export interface AuthorItem {
  id: string;
  fullName: string;
  isActive: boolean;
  createdAt: string;
  updatedAt: string;
}

export interface CategoryItem {
  id: string;
  name: string;
  code?: string | null;
  parentId?: string | null;
  isActive: boolean;
  createdAt: string;
  updatedAt: string;
}

export interface BookItem {
  id: string;
  title: string;
  subtitle?: string | null;
  isbn?: string | null;
  language?: string | null;
  publishYear?: number | null;
  libraryBranchId: string;
  isActive: boolean;
  createdAt: string;
  updatedAt: string;
}

export interface BookCopyItem {
  id: string;
  bookId: string;
  inventoryNumber: string;
  libraryBranchId: string;
  status:
    | "AVAILABLE"
    | "RESERVED"
    | "LOANED"
    | "LOST"
    | "ARCHIVED"
    | "DAMAGED"
    | "WRITTEN_OFF";
  createdAt: string;
  updatedAt: string;
}

// Typed API stubs for the next phase (forms + tables + request state management).
export const catalogApi = {
  async listAuthors(): Promise<AuthorItem[]> {
    return [];
  },
  async listCategories(): Promise<CategoryItem[]> {
    return [];
  },
  async listBooks(): Promise<BookItem[]> {
    return [];
  },
  async listBookCopies(bookId: string): Promise<BookCopyItem[]> {
    void bookId;
    return [];
  },
};
