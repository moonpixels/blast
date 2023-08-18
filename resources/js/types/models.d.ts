export type Team = {
  id: string
  name: string
  personal_team: boolean
  created_at: string
  updated_at: string
  owner?: User
  members?: User[]
  members_count?: number
  invitations?: TeamInvitation[]
  invitations_count?: number
  links?: Link[]
  links_count?: number
}

export type User = {
  id: string
  name: string
  email: string
  initials: string
  two_factor_enabled?: boolean
  current_team?: Team
  teams: ?Team[]
}

export type TeamInvitation = {
  id: string
  email: string
  created_at: string
  updated_at: string
  team?: Team
}

export type Link = {
  id: string
  short_url: string
  destination_url: string
  alias: string
  has_password: boolean
  visit_limit: number | null
  expires_at: string | null
  created_at: string
  updated_at: string
  team?: Team
}
