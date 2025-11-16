// Shared types for Alanya TEKMER

export interface Admin {
  id: string;
  username: string;
  role: string;
  created_at: Date;
  updated_at: Date;
}

export interface TeamMember {
  id: string;
  photo_url: string;
  name: string;
  position: string;
  order_index: number;
  created_at: Date;
  updated_at: Date;
}

export interface Event {
  id: string;
  type: 'event' | 'announcement';
  title: string;
  description: string;
  event_date?: Date;
  photos: string[];
  created_at: Date;
  updated_at: Date;
}

export interface Company {
  id: string;
  name: string;
  logo_url?: string;
  description?: string;
  contact_person?: string;
  phone?: string;
  instagram?: string;
  linkedin?: string;
  website?: string;
  whatsapp?: string;
  created_at: Date;
  updated_at: Date;
}

export interface Application {
  id: string;
  project_type: string;
  business_idea: string;
  full_name: string;
  phone: string;
  tc_no: string;
  email: string;
  university?: string;
  company_name?: string;
  requested_area: string;
  expectations: string;
  project_name: string;
  team_size: number;
  project_summary: string;
  project_file_url: string;
  status: 'pending' | 'approved' | 'rejected' | 'revision';
  rejection_reason?: string;
  created_at: Date;
  updated_at: Date;
}

export interface ContactInfo {
  id: string;
  phone: string;
  address: string;
  email: string;
  google_maps_url?: string;
  facebook?: string;
  youtube?: string;
  linkedin?: string;
  instagram?: string;
  updated_at: Date;
}

export interface ComboboxOption {
  id: string;
  field_name: string;
  option_value: string;
  order_index: number;
  created_at: Date;
}

export interface PageAnalytics {
  id: string;
  page_path: string;
  ip_address: string;
  unique_ip_hash: string;
  user_agent?: string;
  referer?: string;
  visited_at: Date;
}

export interface CookieConsent {
  id: string;
  ip_address: string;
  consent_given: boolean;
  created_at: Date;
}

