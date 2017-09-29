<?php
/**
 * Description of BP_Messages_Box_Template
 *
 * @author BuddyBoss
 */
class BP_Messages_Box_Template_Extend extends BP_Messages_Box_Template {
    
    public function __construct( $user_id, $box, $per_page, $max, $type, $search_terms, $page_arg = 'mpage' ) {
		$this->pag_page = isset( $_GET[$page_arg] ) ? intval( $_GET[$page_arg] ) : 1;
		$this->pag_num  = isset( $_GET['num'] )   ? intval( $_GET['num'] )   : $per_page;

		$this->user_id      = $user_id;
		$this->box          = $box;
		$this->type         = $type;
		$this->search_terms = $search_terms;
                
		if ( 'notices' == $this->box ) {
			$this->threads = BP_Messages_Notice::get_notices( array(
				'pag_num'  => $this->pag_num,
				'pag_page' => $this->pag_page
			) );
		} else {
			$threads = BP_Messages_Thread_Extend::get_current_threads_for_user( $this->user_id, $this->box, $this->type, $this->pag_num, $this->pag_page, $this->search_terms );

			$this->threads            = $threads['threads'];
			$this->total_thread_count = $threads['total'];
		}

		if ( !$this->threads ) {
			$this->thread_count       = 0;
			$this->total_thread_count = 0;
		} else {
			$total_notice_count = BP_Messages_Notice::get_total_notice_count();

			if ( !$max || $max >= (int) $total_notice_count ) {
				if ( 'notices' == $this->box ) {
					$this->total_thread_count = (int) $total_notice_count;
				}
			} else {
				$this->total_thread_count = (int) $max;
			}

			if ( $max ) {
				if ( $max >= count( $this->threads ) ) {
					$this->thread_count = count( $this->threads );
				} else {
					$this->thread_count = (int) $max;
				}
			} else {
				$this->thread_count = count( $this->threads );
			}
		}

		if ( (int) $this->total_thread_count && (int) $this->pag_num ) {
			$pag_args = array(
				$page_arg => '%#%',
			);

			if ( defined( 'DOING_AJAX' ) && true === (bool) DOING_AJAX ) {
				$base = esc_url(remove_query_arg( 's', wp_get_referer() ));
			} else {
				$base = '';
			}

			if ( ! empty( $this->search_terms ) ) {
				$pag_args['s'] = $this->search_terms;
			}

			$this->pag_links = paginate_links( array(
				'base'      => esc_url(add_query_arg( $pag_args, $base )),
				'format'    => '',
				'total'     => ceil( (int) $this->total_thread_count / (int) $this->pag_num ),
				'current'   => $this->pag_page,
				'prev_text' => _x( '&larr;', 'Message pagination previous text', 'buddyboss-inbox' ),
				'next_text' => _x( '&rarr;', 'Message pagination next text', 'buddyboss-inbox' ),
				'mid_size'  => 1
			) );
		}
	}
}