declare namespace App.Domain.Link.Data {
  export type LinkData = {
    team_id?: string
    destination_url?: string
    alias?: string
    password?: string | null
    visit_limit?: number | null
    expires_at?: string | null
  }
}
declare namespace App.Domain.Redirect.Data {
  export type RedirectData = {
    password?: string
  }
}
declare namespace App.Domain.Redirect.Enums {
  export type DeviceTypes = 'desktop' | 'mobile' | 'tablet'
}
declare namespace App.Domain.Team.Data {
  export type TeamData = {
    name?: string
    owner_id?: string
  }
  export type TeamInvitationData = {
    team_id?: string
    email?: string
  }
}
declare namespace App.Domain.User.Data {
  export type UserData = {
    name?: string
    email?: string
    password?: string
    current_team_id?: string
  }
}
