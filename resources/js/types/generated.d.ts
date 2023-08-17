namespace App.Domain.Link.Data {
  export type LinkData = {
    team_id?: string
    destination_url?: string
    alias?: string
    password?: string | null
    visit_limit?: number | null
    expires_at?: string | null
  }
}
namespace App.Domain.Redirect.Data {
  export type RedirectData = {
    password?: string
  }
}
namespace App.Domain.Redirect.Enums {
  export type DeviceTypes = 'desktop' | 'mobile' | 'tablet'
}
namespace App.Domain.Team.Data {
  export type TeamData = {
    name?: string
    owner_id?: string
  }
  export type TeamInvitationData = {
    team_id?: string
    email?: string
  }
  export type UserData = {
    name?: string
    email?: string
    password?: string
    current_team_id?: string
  }
}
