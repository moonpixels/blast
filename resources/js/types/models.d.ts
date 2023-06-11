export interface Team {
  id: string
  owner_id: string
  name: string
  personal_team: boolean
  created_at: string
  updated_at: string
}

export interface CurrentUser extends User {
  current_team_id: string
  two_factor_enabled: boolean
  teams: Team[]
}

export interface User {
  id: string
  name: string
  email: string
  initials: string
}

export interface TeamInvitation {
  id: string
  team_id: string
  email: string
  created_at: string
  updated_at: string
}
